<?php
if (!defined('ABSPATH')) exit;

?>
<div class="wrap">
    <h1><?php esc_html_e('API Logs', 'mwpal-api-logger'); ?></h1>

    <form method="get" class="mwpal-search-form">

        <input type="text" name="mwpal_search" value="<?php echo esc_attr($search_query); ?>"
            placeholder="<?php esc_attr_e('Search...', 'mwpal-api-logger'); ?>" />
        <button type="submit" class="button button-primary"><?php esc_html_e('Search', 'mwpal-api-logger'); ?></button>

        <?php if (!empty($search_query)): ?>

            <a href="<?php echo esc_url(remove_query_arg('mwpal_search')); ?>"
                class="button"><?php esc_html_e('Clear Search', 'mwpal-api-logger'); ?></a>
        <?php endif; ?>

    </form>

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
            <?php if (!empty($logs)): ?>
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
                                <pre>
<?php
                    $decoded_response = json_decode($log->response, true);
                    if (is_string($decoded_response)) {
                        $decoded_response = json_decode($decoded_response, true);
                    }

                    if ($decoded_response) {
                        echo esc_html(json_encode($decoded_response, JSON_PRETTY_PRINT));
                    } else {
                        echo esc_html($log->response);
                    }
?>
                                    </pre>
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
                'prev_text' => __('« Previous', 'mwpal-api-logger'),
                'next_text' => __('Next »', 'mwpal-api-logger'),
                'add_args' => ['mwpal_search' => urlencode($search_query)],
            ]);
            ?>
        </div>
    <?php endif; ?>
</div>