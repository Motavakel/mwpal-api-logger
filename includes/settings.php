<?php
if (!defined('ABSPATH')) exit;

class MWPAL_Settings
{

    private static $option_name = 'mwpal_api_settings';

    public function __construct()
    {
        add_action('admin_init', [$this, 'mwpal_register_settings']);
    }

    public function mwpal_register_settings()
    {

        register_setting('mwpal_settings_group', self::$option_name);

        add_settings_section('mwpal_main_section', esc_html__('Main Settings', 'mwpal-api-logger'), null, 'mwpal-settings');
        add_settings_field(
            'mwpal_api_url',
            esc_html__('API Endpoint URL', 'mwpal-api-logger'),
            [$this, 'mwpal_api_url_field'],
            'mwpal-settings',
            'mwpal_main_section'
        );
    }

    public function mwpal_api_url_field()
    {
        $options = get_option(self::$option_name);
?>
<input type="url" name="mwpal_api_settings[mwpal_api_url]"
    value="<?php echo esc_attr($options['mwpal_api_url'] ?? ''); ?>" class="regular-text" />
<?php
    }

    public static function mwpal_render()
    {
    ?>
<div class="wrap">
    <h1><?php esc_html_e('API Logger Settings', 'mwpal-api-logger'); ?></h1>
    <form method="post" action="options.php">
        <?php
                settings_fields('mwpal_settings_group');
                do_settings_sections('mwpal-settings');
                submit_button();
                ?>
    </form>
</div>
<?php
    }

    public static function mwpal_get_api_url()
    {
        $options = get_option(self::$option_name);
        return $options['mwpal_api_url'] ?? '';
    }
}