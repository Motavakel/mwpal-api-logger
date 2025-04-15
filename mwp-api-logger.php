<?php

/**
 * Plugin Name: WP API Logger
 * Description: A plugin to send API requests and log the responses.
 * Version: 1.0.0
 * Author: Milad Motavakel
 * Text Domain: mwpal-api-logger
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) exit;

define('MWPAL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MWPAL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MWPAL_VERSION', '1.0.0');


require_once MWPAL_PLUGIN_PATH . 'includes/plugin.php';
require_once MWPAL_PLUGIN_PATH . 'includes/action.php';
require_once MWPAL_PLUGIN_PATH . 'includes/database.php';
require_once MWPAL_PLUGIN_PATH . 'includes/logger.php';
require_once MWPAL_PLUGIN_PATH . 'includes/settings.php';

function mwpal_load_textdomain()
{
    load_plugin_textdomain('mwpal-api-logger', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'mwpal_load_textdomain');
function mwpal_initialize_plugin()
{
    if (is_admin()) {
        new MWPAL_Plugin();
    }
}
add_action('plugins_loaded', 'mwpal_initialize_plugin');
register_activation_hook(__FILE__, 'MWPAL_Database::create_table');