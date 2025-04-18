<?php
if (!defined('ABSPATH')) exit;

class MWPAL_Action
{

    public function __construct()
    {
        add_action('admin_post_mwpal_send_request', [$this, 'mwpal_handle_request']);
    }

    public static function mwpal_render()
    {
?>
        <div class="wrap">
            <h1><?php _e('Send API Request', 'mwpal-api-logger'); ?></h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="mwpal_send_request">
                <?php wp_nonce_field('mwpal_send_request_nonce', 'mwpal_nonce'); ?>
                <button type="submit" class="mwpal-post-btn"><?php esc_html_e('Post', 'mwpal-api-logger'); ?></button>
            </form>

            <form action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="mwpal_send_request">
                <?php wp_nonce_field('mwpal_send_request_nonce', 'mwpal_nonce'); ?>
                <button type="submit" class="mwpal-get-btn"><?php esc_html_e('Get', 'mwpal-api-logger'); ?></button>
            </form>
        </div>
<?php
    }

    public function mwpal_handle_request()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $request_data = $method === 'POST' ? $_POST : $_GET;

        if (!isset($request_data['mwpal_nonce']) || !wp_verify_nonce($request_data['mwpal_nonce'], 'mwpal_send_request_nonce')) {
            wp_die(esc_html__('Failed...', 'mwpal-api-logger'));
        }

        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('Unauthoriz...', 'mwpal-api-logger'));
        }

        $url = MWPAL_Settings::mwpal_get_api_url();
        if (empty($url)) {
            wp_die(esc_html__('Missing...', 'mwpal-api-logger'));
        }

        if ($method === 'POST') {
            $response = wp_remote_post($url, [
                'timeout' => 10,
                'headers' => ['Content-Type' => 'application/json'],
            ]);
        } else {
            $response = wp_remote_get($url, [
                'timeout' => 10,
                'headers' => ['Content-Type' => 'application/json'],
            ]);
        }

        if (is_wp_error($response)) {
            $body = $response->get_error_message();
        } else {
            $body = wp_remote_retrieve_body($response);
        }

        global $wpdb;
        $wpdb->insert("{$wpdb->prefix}mwpal_logs", [
            'request_method' => $method,
            'request_url' => esc_url_raw($url),
            'response' => wp_json_encode($body),
        ]);

        wp_redirect(admin_url('admin.php?page=mwpal-logs'));
        exit;
    }
}
