<?php
if (!defined('ABSPATH')) exit;

class MWPAL_Logger
{
    public static function mwpal_render()
    {
        global $wpdb;

        $table_name = "{$wpdb->prefix}mwpal_logs";
        $per_page = 2;
        $current_page = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
        $offset = ($current_page - 1) * $per_page;

        $search_query = isset($_GET['mwpal_search']) ? sanitize_text_field($_GET['mwpal_search']) : '';
        $where_sql = '';
        if (!empty($search_query)) {
            $like = '%' . $wpdb->esc_like($search_query) . '%';
            $where_sql = $wpdb->prepare(" WHERE request_url LIKE %s OR request_method LIKE %s", $like, $like);
        }

        $sql = "SELECT * FROM $table_name $where_sql ORDER BY id DESC LIMIT %d OFFSET %d";
        $count_sql = "SELECT COUNT(*) FROM $table_name $where_sql";

        $total_logs = $wpdb->get_var($count_sql);
        $total_pages = ceil($total_logs / $per_page);

        $logs = $wpdb->get_results($wpdb->prepare($sql, $per_page, $offset));

        include MWPAL_PLUGIN_TEMPLATE . '/logger-page.php';
    }
}
