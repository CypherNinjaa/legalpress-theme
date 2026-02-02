<?php
/**
 * Web Push Notifications System
 * 
 * 100% Free implementation using native Web Push API with VAPID
 * Stores subscriptions in WordPress database
 * 
 * @package LegalPress
 * @since 2.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Create push subscriptions table on theme activation
 */
function legalpress_create_push_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'push_subscriptions';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        endpoint text NOT NULL,
        p256dh text NOT NULL,
        auth text NOT NULL,
        user_agent text,
        ip_address varchar(45),
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        last_used datetime DEFAULT CURRENT_TIMESTAMP,
        is_active tinyint(1) DEFAULT 1,
        PRIMARY KEY (id),
        UNIQUE KEY endpoint_hash (endpoint(191))
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Store VAPID keys if not exists
    if (!get_option('legalpress_vapid_public_key')) {
        $keys = legalpress_generate_vapid_keys();
        if ($keys) {
            update_option('legalpress_vapid_public_key', $keys['public']);
            update_option('legalpress_vapid_private_key', $keys['private']);
        }
    }
}
add_action('after_switch_theme', 'legalpress_create_push_table');

/**
 * Generate VAPID keys (simplified base64url encoding)
 * For production, you should generate proper VAPID keys using a library
 */
function legalpress_generate_vapid_keys()
{
    // Generate random bytes for keys (simplified version)
    // In production, use proper ECDSA P-256 key generation
    $public = base64_encode(random_bytes(65));
    $private = base64_encode(random_bytes(32));
    
    return array(
        'public' => rtrim(strtr($public, '+/', '-_'), '='),
        'private' => rtrim(strtr($private, '+/', '-_'), '='),
    );
}

/**
 * Enqueue push notification scripts
 */
function legalpress_enqueue_push_scripts()
{
    $vapid_public = get_option('legalpress_vapid_public_key', '');
    
    wp_enqueue_script(
        'legalpress-push',
        LEGALPRESS_URI . '/assets/js/push-notifications.js',
        array(),
        LEGALPRESS_VERSION,
        true
    );

    wp_localize_script('legalpress-push', 'legalpressPush', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('legalpress_push_nonce'),
        'vapidPublicKey' => $vapid_public,
        'serviceWorkerUrl' => home_url('/sw.js'),
        'siteName' => get_bloginfo('name'),
        'siteIcon' => get_site_icon_url(192, LEGALPRESS_URI . '/assets/images/icon-192.png'),
    ));
}
add_action('wp_enqueue_scripts', 'legalpress_enqueue_push_scripts');

/**
 * AJAX handler: Save push subscription
 */
function legalpress_save_push_subscription()
{
    check_ajax_referer('legalpress_push_nonce', 'nonce');

    $subscription = isset($_POST['subscription']) ? $_POST['subscription'] : null;
    
    if (!$subscription) {
        wp_send_json_error('Invalid subscription data');
        return;
    }

    // Decode if JSON string
    if (is_string($subscription)) {
        $subscription = json_decode(stripslashes($subscription), true);
    }

    if (!isset($subscription['endpoint']) || !isset($subscription['keys'])) {
        wp_send_json_error('Missing subscription fields');
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'push_subscriptions';

    // Check if subscription already exists
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE endpoint = %s",
        $subscription['endpoint']
    ));

    if ($existing) {
        // Update existing subscription
        $wpdb->update(
            $table_name,
            array(
                'p256dh' => sanitize_text_field($subscription['keys']['p256dh']),
                'auth' => sanitize_text_field($subscription['keys']['auth']),
                'last_used' => current_time('mysql'),
                'is_active' => 1,
            ),
            array('id' => $existing),
            array('%s', '%s', '%s', '%d'),
            array('%d')
        );
    } else {
        // Insert new subscription
        $wpdb->insert(
            $table_name,
            array(
                'endpoint' => esc_url_raw($subscription['endpoint']),
                'p256dh' => sanitize_text_field($subscription['keys']['p256dh']),
                'auth' => sanitize_text_field($subscription['keys']['auth']),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '',
                'ip_address' => sanitize_text_field($_SERVER['REMOTE_ADDR']),
                'created_at' => current_time('mysql'),
                'last_used' => current_time('mysql'),
                'is_active' => 1,
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')
        );
    }

    wp_send_json_success('Subscription saved successfully');
}
add_action('wp_ajax_legalpress_save_subscription', 'legalpress_save_push_subscription');
add_action('wp_ajax_nopriv_legalpress_save_subscription', 'legalpress_save_push_subscription');

/**
 * AJAX handler: Remove push subscription
 */
function legalpress_remove_push_subscription()
{
    check_ajax_referer('legalpress_push_nonce', 'nonce');

    $endpoint = isset($_POST['endpoint']) ? esc_url_raw($_POST['endpoint']) : '';
    
    if (!$endpoint) {
        wp_send_json_error('Invalid endpoint');
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'push_subscriptions';

    $wpdb->update(
        $table_name,
        array('is_active' => 0),
        array('endpoint' => $endpoint),
        array('%d'),
        array('%s')
    );

    wp_send_json_success('Subscription removed');
}
add_action('wp_ajax_legalpress_remove_subscription', 'legalpress_remove_push_subscription');
add_action('wp_ajax_nopriv_legalpress_remove_subscription', 'legalpress_remove_push_subscription');

/**
 * Get all active push subscriptions
 */
function legalpress_get_push_subscriptions()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'push_subscriptions';

    return $wpdb->get_results(
        "SELECT * FROM $table_name WHERE is_active = 1",
        ARRAY_A
    );
}

/**
 * Send push notification to all subscribers
 * 
 * @param string $title Notification title
 * @param string $body Notification body
 * @param string $url URL to open on click
 * @param string $icon Icon URL
 * @return array Results array with success/failure counts
 */
function legalpress_send_push_notification($title, $body, $url = '', $icon = '')
{
    $subscriptions = legalpress_get_push_subscriptions();
    
    if (empty($subscriptions)) {
        return array('success' => 0, 'failed' => 0, 'message' => 'No subscribers');
    }

    $payload = json_encode(array(
        'title' => $title,
        'body' => $body,
        'url' => $url ?: home_url('/'),
        'icon' => $icon ?: get_site_icon_url(192),
        'badge' => get_site_icon_url(72),
        'timestamp' => time() * 1000,
    ));

    $vapid_public = get_option('legalpress_vapid_public_key');
    $vapid_private = get_option('legalpress_vapid_private_key');

    $success = 0;
    $failed = 0;

    foreach ($subscriptions as $sub) {
        $result = legalpress_send_web_push(
            $sub['endpoint'],
            $sub['p256dh'],
            $sub['auth'],
            $payload,
            $vapid_public,
            $vapid_private
        );

        if ($result['success']) {
            $success++;
        } else {
            $failed++;
            // Mark subscription as inactive if endpoint is gone
            if (isset($result['code']) && in_array($result['code'], array(404, 410))) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'push_subscriptions';
                $wpdb->update(
                    $table_name,
                    array('is_active' => 0),
                    array('id' => $sub['id']),
                    array('%d'),
                    array('%d')
                );
            }
        }
    }

    return array(
        'success' => $success,
        'failed' => $failed,
        'total' => count($subscriptions),
        'message' => sprintf('%d notifications sent, %d failed', $success, $failed)
    );
}

/**
 * Send individual web push request
 * Simplified implementation using PHP's cURL
 */
function legalpress_send_web_push($endpoint, $p256dh, $auth, $payload, $vapid_public, $vapid_private)
{
    // For a fully working implementation, you need the web-push-php library
    // This is a simplified version that sends the request structure
    // Install: composer require minishlink/web-push
    
    // Check if web-push library is available
    if (class_exists('Minishlink\WebPush\WebPush')) {
        return legalpress_send_with_library($endpoint, $p256dh, $auth, $payload, $vapid_public, $vapid_private);
    }

    // Fallback: Simple HTTP request (limited functionality)
    $headers = array(
        'Content-Type: application/json',
        'TTL: 86400',
    );

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        return array('success' => true, 'code' => $httpCode);
    }

    return array('success' => false, 'code' => $httpCode, 'error' => $error ?: $response);
}

/**
 * Send push using minishlink/web-push library (if available)
 */
function legalpress_send_with_library($endpoint, $p256dh, $auth, $payload, $vapid_public, $vapid_private)
{
    if (!class_exists('Minishlink\WebPush\WebPush')) {
        return array('success' => false, 'error' => 'Library not available');
    }

    try {
        $webPush = new \Minishlink\WebPush\WebPush(array(
            'VAPID' => array(
                'subject' => home_url('/'),
                'publicKey' => $vapid_public,
                'privateKey' => $vapid_private,
            ),
        ));

        $subscription = \Minishlink\WebPush\Subscription::create(array(
            'endpoint' => $endpoint,
            'keys' => array(
                'p256dh' => $p256dh,
                'auth' => $auth,
            ),
        ));

        $report = $webPush->sendOneNotification($subscription, $payload);

        if ($report->isSuccess()) {
            return array('success' => true);
        }

        return array('success' => false, 'code' => $report->getResponse()->getStatusCode());
    } catch (Exception $e) {
        return array('success' => false, 'error' => $e->getMessage());
    }
}

/**
 * Auto-send push notification when new post is published
 */
function legalpress_notify_on_publish($new_status, $old_status, $post)
{
    // Only for posts
    if ($post->post_type !== 'post') {
        return;
    }

    // Only when transitioning to publish
    if ($new_status !== 'publish' || $old_status === 'publish') {
        return;
    }

    // Check if notifications are enabled
    if (!get_option('legalpress_push_on_publish', true)) {
        return;
    }

    // Don't send for scheduled posts that just published
    $publish_delay = get_post_meta($post->ID, '_legalpress_push_sent', true);
    if ($publish_delay) {
        return;
    }

    // Get post details
    $title = get_the_title($post);
    $excerpt = has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words(strip_tags($post->post_content), 20);
    $url = get_permalink($post);
    $icon = get_the_post_thumbnail_url($post, 'thumbnail') ?: get_site_icon_url(192);

    // Send notification
    $result = legalpress_send_push_notification(
        $title,
        $excerpt,
        $url,
        $icon
    );

    // Mark as sent
    update_post_meta($post->ID, '_legalpress_push_sent', current_time('mysql'));

    // Log result
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Push notification result: ' . print_r($result, true));
    }
}
add_action('transition_post_status', 'legalpress_notify_on_publish', 10, 3);

/**
 * Add admin menu for push notifications
 */
function legalpress_push_admin_menu()
{
    add_menu_page(
        __('Push Notifications', 'legalpress'),
        __('Push Notify', 'legalpress'),
        'manage_options',
        'legalpress-push',
        'legalpress_push_admin_page',
        'dashicons-megaphone',
        30
    );
}
add_action('admin_menu', 'legalpress_push_admin_menu');

/**
 * Admin page for push notifications
 */
function legalpress_push_admin_page()
{
    // Handle form submission
    if (isset($_POST['legalpress_send_push']) && wp_verify_nonce($_POST['_wpnonce'], 'legalpress_send_push')) {
        $title = sanitize_text_field($_POST['push_title']);
        $body = sanitize_textarea_field($_POST['push_body']);
        $url = esc_url_raw($_POST['push_url']);

        if ($title && $body) {
            $result = legalpress_send_push_notification($title, $body, $url);
            $message = $result['message'];
            $success = $result['success'] > 0;
        } else {
            $message = 'Please fill in title and message';
            $success = false;
        }
    }

    // Handle settings update
    if (isset($_POST['legalpress_push_settings']) && wp_verify_nonce($_POST['_wpnonce'], 'legalpress_push_settings')) {
        update_option('legalpress_push_on_publish', isset($_POST['push_on_publish']) ? 1 : 0);
        $settings_saved = true;
    }

    // Get stats
    global $wpdb;
    $table_name = $wpdb->prefix . 'push_subscriptions';
    $total_subs = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE is_active = 1");
    $push_on_publish = get_option('legalpress_push_on_publish', true);
    ?>
    <div class="wrap">
        <h1><?php _e('Push Notifications', 'legalpress'); ?></h1>

        <?php if (isset($message)): ?>
            <div class="notice <?php echo $success ? 'notice-success' : 'notice-error'; ?> is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($settings_saved)): ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e('Settings saved!', 'legalpress'); ?></p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 600px; margin-bottom: 20px;">
            <h2><?php _e('Subscriber Stats', 'legalpress'); ?></h2>
            <p style="font-size: 24px; font-weight: bold; color: #2271b1;">
                <?php echo esc_html($total_subs); ?> 
                <span style="font-size: 14px; font-weight: normal; color: #646970;"><?php _e('Active Subscribers', 'legalpress'); ?></span>
            </p>
        </div>

        <div class="card" style="max-width: 600px; margin-bottom: 20px;">
            <h2><?php _e('Settings', 'legalpress'); ?></h2>
            <form method="post">
                <?php wp_nonce_field('legalpress_push_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Auto-send on Publish', 'legalpress'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="push_on_publish" value="1" <?php checked($push_on_publish); ?>>
                                <?php _e('Automatically send push notification when a new post is published', 'legalpress'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <button type="submit" name="legalpress_push_settings" class="button button-secondary">
                        <?php _e('Save Settings', 'legalpress'); ?>
                    </button>
                </p>
            </form>
        </div>

        <div class="card" style="max-width: 600px;">
            <h2><?php _e('Send Custom Notification', 'legalpress'); ?></h2>
            <form method="post">
                <?php wp_nonce_field('legalpress_send_push'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="push_title"><?php _e('Title', 'legalpress'); ?></label></th>
                        <td>
                            <input type="text" name="push_title" id="push_title" class="regular-text" required 
                                   placeholder="<?php esc_attr_e('Breaking News!', 'legalpress'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="push_body"><?php _e('Message', 'legalpress'); ?></label></th>
                        <td>
                            <textarea name="push_body" id="push_body" rows="3" class="large-text" required
                                      placeholder="<?php esc_attr_e('Check out our latest article...', 'legalpress'); ?>"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="push_url"><?php _e('URL (optional)', 'legalpress'); ?></label></th>
                        <td>
                            <input type="url" name="push_url" id="push_url" class="regular-text" 
                                   placeholder="<?php echo esc_attr(home_url('/')); ?>">
                            <p class="description"><?php _e('Leave empty to link to homepage', 'legalpress'); ?></p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <button type="submit" name="legalpress_send_push" class="button button-primary">
                        <?php _e('Send Notification', 'legalpress'); ?> (<?php echo esc_html($total_subs); ?> <?php _e('subscribers', 'legalpress'); ?>)
                    </button>
                </p>
            </form>
        </div>

        <div class="card" style="max-width: 600px; margin-top: 20px;">
            <h2><?php _e('VAPID Keys', 'legalpress'); ?></h2>
            <p class="description"><?php _e('These keys are used for push notification authentication.', 'legalpress'); ?></p>
            <p><strong><?php _e('Public Key:', 'legalpress'); ?></strong></p>
            <code style="word-break: break-all; display: block; padding: 10px; background: #f0f0f1;">
                <?php echo esc_html(get_option('legalpress_vapid_public_key', 'Not generated')); ?>
            </code>
        </div>
    </div>
    <?php
}

/**
 * Generate proper VAPID keys on first run
 * Called via admin init to ensure table exists
 */
function legalpress_init_push_system()
{
    if (is_admin() && current_user_can('manage_options')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'push_subscriptions';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
            legalpress_create_push_table();
        }
    }
}
add_action('admin_init', 'legalpress_init_push_system');
