<?php
/**
 * Template Name: Contact
 * Description: Lightweight, secure contact form (no JS). Uses wp_mail with nonce + honeypot.
 */

if (! defined('ABSPATH')) {
    exit;
}

// Handle form POST
function gp_spotlight_handle_contact_form(): array {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return ['status' => 'idle', 'message' => ''];
    }

    // Verify nonce
    if (! isset($_POST['gp_contact_nonce']) || ! wp_verify_nonce(sanitize_text_field((string) $_POST['gp_contact_nonce']), 'gp_contact')) {
        return ['status' => 'error', 'message' => esc_html__('Security check failed. Please try again.', 'gp-spotlight')];
    }

    // Honeypot (should stay empty)
    $hp = isset($_POST['website']) ? trim((string) $_POST['website']) : '';
    if ($hp !== '') {
        return ['status' => 'error', 'message' => esc_html__('Spam detected.', 'gp-spotlight')];
    }

    // Basic rate limit per IP (2 min)
    $ip      = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field((string) $_SERVER['REMOTE_ADDR']) : 'unknown';
    $rl_key  = 'gp_contact_rl_' . md5($ip);
    $blocked = get_transient($rl_key);
    if ($blocked) {
        return ['status' => 'error', 'message' => esc_html__('Please wait a moment before sending again.', 'gp-spotlight')];
    }

    // Sanitize inputs
    $name    = isset($_POST['name']) ? sanitize_text_field((string) $_POST['name']) : '';
    $email   = isset($_POST['email']) ? sanitize_email((string) $_POST['email']) : '';
    $message = isset($_POST['message']) ? wp_strip_all_tags((string) $_POST['message']) : '';

    if ($name === '' || $email === '' || $message === '' || ! is_email($email)) {
        return ['status' => 'error', 'message' => esc_html__('Please fill in all required fields with a valid email.', 'gp-spotlight')];
    }

    // Compose & send
    $to      = get_option('admin_email');
    $subject = sprintf(__('New contact message from %s', 'gp-spotlight'), get_bloginfo('name'));
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];
    $body    = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}\n";

    $sent = wp_mail($to, $subject, $body, $headers);

    // Set rate-limit window even on success to prevent abuse
    set_transient($rl_key, 1, 120);

    if (! $sent) {
        return ['status' => 'error', 'message' => esc_html__('Your message could not be sent. Please try again later.', 'gp-spotlight')];
    }

    return ['status' => 'success', 'message' => esc_html__('Thank you! Your message has been sent.', 'gp-spotlight')];
}

// If this file is loaded as a template, intercept and render content below.
if (is_page_template('page-contact.php')) {
    get_header();
    $result = gp_spotlight_handle_contact_form();
    ?>
    <div class="spotlight-content">
      <div class="container--spotlight" style="max-width:720px;">
        <article class="post">
          <header style="margin-top:16px;">
            <h1 class="post-title" style="font-size:30px;"><?php the_title(); ?></h1>
          </header>

          <?php if ($result['status'] === 'error') : ?>
            <div role="alert" style="border:1px solid #ef4444;padding:12px;border-radius:8px;color:#b91c1c;margin-top:12px;">
              <?php echo esc_html($result['message']); ?>
            </div>
          <?php elseif ($result['status'] === 'success') : ?>
            <div role="status" style="border:1px solid #22c55e;padding:12px;border-radius:8px;color:#166534;margin-top:12px;">
              <?php echo esc_html($result['message']); ?>
            </div>
          <?php endif; ?>

          <form action="<?php echo esc_url(get_permalink()); ?>" method="post" style="margin-top:16px;display:grid;gap:12px;">
            <?php wp_nonce_field('gp_contact', 'gp_contact_nonce'); ?>
            <!-- Honeypot -->
            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" style="display:none" aria-hidden="true" />

            <label>
              <?php echo esc_html__('Name', 'gp-spotlight'); ?>*
              <input type="text" name="name" required
                     style="width:100%;height:44px;border:1px solid var(--border);border-radius:8px;padding:0 12px;" />
            </label>

            <label>
              <?php echo esc_html__('Email', 'gp-spotlight'); ?>*
              <input type="email" name="email" required
                     style="width:100%;height:44px;border:1px solid var(--border);border-radius:8px;padding:0 12px;" />
            </label>

            <label>
              <?php echo esc_html__('Message', 'gp-spotlight'); ?>*
              <textarea name="message" rows="6" required
                        style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;"></textarea>
            </label>

            <button type="submit" class="button btn" style="height:44px;border:1px solid var(--border);border-radius:8px;background:var(--muted-bg);color:var(--text);">
              <?php echo esc_html__('Send', 'gp-spotlight'); ?>
            </button>
          </form>
        </article>
      </div>
    </div>
    <?php
    get_footer();
    // Prevent default page content from rendering twice if template is included.
    exit;
}