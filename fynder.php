<?php
/*
Plugin Name: Fynder
*/

////////////////////////////////////////////////////////////////////////////////

function add_fynder_script() {
    wp_enqueue_script('fynder-widget', '//m.fynder.io/fynder-loader.js');
}

add_action('wp_enqueue_scripts', 'add_fynder_script');

////////////////////////////////////////////////////////////////////////////////

function fynder_shortcode($atts, $content = null) {
    if(!$content) {
        $content = __('View Our Schedule', 'wordpress');
    }
    return "<a class=\"fynder-show-schedule\" href=\"#\">$content</a>";
}

add_shortcode('booking-button', 'fynder_shortcode');

////////////////////////////////////////////////////////////////////////////////

function activate_fynder() {
    $options = get_option('fynder_settings');
    if ($options && $options[business_id]) {
        echo "<script type=\"text/javascript\">window.addEventListener(\"load\", function() {fynder_loader.loader.parent($options[business_id])});</script>";
    }
}

add_action('wp_footer', 'activate_fynder');

////////////////////////////////////////////////////////////////////////////////

function fynder_add_admin_menu() {
    add_options_page('Fynder', 'Fynder', 'manage_options', 'fynder', 'fynder_options_page');
}

function fynder_options_page() {
    ?>
    <form action='options.php' method='post'>

    <h2>Fynder</h2>

<?php
    settings_fields( 'pluginPage' );
    do_settings_sections( 'pluginPage' );
    submit_button();
    ?>

    </form>
<?php
}

add_action('admin_menu', 'fynder_add_admin_menu');

////////////////////////////////////////////////////////////////////////////////

function fynder_settings_init() {
    register_setting('pluginPage', 'fynder_settings');

    add_settings_section(
        'fynder_pluginPage_section',
        __('Your Fynder Account', 'wordpress'),
        'fynder_settings_section_callback',
        'pluginPage'
    );


    add_settings_field(
        'business_id',
        __('Business ID', 'wordpress'),
        'business_id_render',
        'pluginPage',
        'fynder_pluginPage_section'
    );
}

function business_id_render() {
    $options = get_option('fynder_settings');
    ?>
    <input type='text' name='fynder_settings[business_id]' value='<?php echo $options['business_id']; ?>'>
<?php
}

function fynder_settings_section_callback(  ) {
    echo __('To add your Fynder schedule to your website, please enter your business id. If you haven\'t created a Fynder account, head over to <a href="//fynder.io">fynder.io</a>. Don\'t worry - we\'ll still be here when you get back! Once you\'ve created a Fynder account, go to <a href="//admin.fynder.io">the admin site</a> and then select <em>Account Settings</em> followed by <em>Fynder Widget</em> to find your business id.', 'wordpress');
}

add_action('admin_init', 'fynder_settings_init');

?>