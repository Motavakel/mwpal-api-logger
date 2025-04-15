<?php
if (!defined('ABSPATH')) exit;

class MWPAL_Logger
{
    public static function mwpal_render()
    {
        global $wpdb;

        $per_page = 2;
        $current_page = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
        $offset = ($current_page - 1) * $per_page;

        $total_logs = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}mwpal_logs");
        $total_pages = ceil($total_logs / $per_page);

        $logs = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}mwpal_logs ORDER BY id DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        ));
?>
<div class="wrap">
    <h1><?php esc_html_e('API Logs', 'mwpal-api-logger'); ?></h1>

    <table class="wp-list-table widefat striped mwpal-logs-table">
        <thead>
            <tr>
                <th><?php esc_html_e('Date', 'mwpal-api-logger'); ?></th>
                <th><?php esc_html_e('Method', 'mwpal-api-logger'); ?></th>
                <th><?php esc_html_e('URL', 'mwpal-api-logger'); ?></th>
                <th><?php esc_html_e('Response', 'mwpal-api-logger'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($logs): ?>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td><?php echo esc_html($log->created_at); ?></td>
                <td>
                    <span class="mwpal-method mwpal-method-<?php echo esc_attr(strtolower($log->request_method)); ?>">
                        <?php echo esc_html($log->request_method); ?>
                    </span>
                </td>
                <td><code class="mwpal-url"><?php echo esc_url($log->request_url); ?></code></td>
                <td>
                    <div class="mwpal-response">
                        <pre><?php
                                                $decoded_response = json_decode($log->response, true);
                                                if ($decoded_response) {
                                                    echo esc_html(json_encode($decoded_response, JSON_PRETTY_PRINT));
                                                } else {
                                                    echo esc_html($log->response);
                                                }
                                                ?></pre>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="4"><?php esc_html_e('No logs found.', 'mwpal-api-logger'); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1): ?>
    <div class="mwpal-pagination">
        <?php
                    echo paginate_links([
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '?paged=%#%',
                        'current' => $current_page,
                        'total' => $total_pages,
                        'prev_text' => esc_html__('&laquo; Previous', 'mwpal-api-logger'),
                        'next_text' => esc_html__('Next &raquo;', 'mwpal-api-logger'),
                    ]);
                    ?>
    </div>
    <?php endif; ?>
</div>
<?php
    }
}