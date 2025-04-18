<?php

if (!defined('ABSPATH')) exit;

class MWPAL_Database
{
    public static function create_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mwpal_logs';
        $charset_collate = $wpdb->get_charset_collate();

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                request_method VARCHAR(10),
                request_url VARCHAR(2083),
                response LONGTEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }
    }
}
