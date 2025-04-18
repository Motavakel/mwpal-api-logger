<?php

if (!defined('ABSPATH')) exit;

class MWPAL_Plugin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menus']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

        new MWPAL_Settings();
        new MWPAL_Action();
        new MWPAL_Logger();
        new MWPAL_Database();
    }

    public function add_admin_menus()
    {
        add_menu_page(
            esc_html__('API Logger', 'mwpal-api-logger'),
            esc_html__('API Logger', 'mwpal-api-logger'),
            'manage_options',
            'mwpal-settings',
            [MWPAL_Settings::class, 'mwpal_render'],
            '',
            1
        );

        add_submenu_page(
            'mwpal-settings',
            esc_html__('Send Request', 'mwpal-api-logger'),
            esc_html__('Send Request', 'mwpal-api-logger'),
            'manage_options',
            'mwpal-action',
            [MWPAL_Action::class, 'mwpal_render']
        );

        add_submenu_page(
            'mwpal-settings',
            esc_html__('API Logs', 'mwpal-api-logger'),
            esc_html__('API Logs', 'mwpal-api-logger'),
            'manage_options',
            'mwpal-logs',
            [MWPAL_Logger::class, 'mwpal_render']
        );
    }

    public function enqueue_admin_assets($hook)
    {
        if (strpos($hook, 'mwpal-') === false) return;
        wp_enqueue_style('mwpal-admin-style', MWPAL_PLUGIN_URL . 'assets/css/style.css', [], MWPAL_VERSION);
        wp_enqueue_script('mwpal-admin-script', MWPAL_PLUGIN_URL . 'assets/js/script.js', ['jquery'], MWPAL_VERSION, true);
    }
}
