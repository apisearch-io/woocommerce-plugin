<?php

require __DIR__ . "/../includes/ApisearchFeed.php";

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://apisearch.io
 * @since      1.0.0
 *
 * @package    Apisearch
 * @subpackage Apisearch/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Apisearch
 * @subpackage Apisearch/admin
 * @author     Apisearch Team <info@apisearch.io>
 */
class Apisearch_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        add_action('admin_menu', array( $this, 'addPluginAdminMenu' ), 9);
        add_action('admin_init', array( $this, 'registerAndBuildFields' ));
        ApisearchFeed::register();
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Apisearch_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Apisearch_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/apisearch-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Apisearch_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Apisearch_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/apisearch-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function addPluginAdminMenu() {
        add_menu_page(
            $this->plugin_name,
            'Apisearch',
            'administrator',
            $this->plugin_name, array( $this, 'displayPluginAdminSettings' ), 'dashicons-chart-area', 26
        );
    }

    public function displayPluginAdminSettings() {
        // set this var to be used in the settings-display view
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
        if(isset($_GET['error_message'])){
            add_action('admin_notices', array($this,'settingsPageSettingsMessages'));
            do_action( 'admin_notices', $_GET['error_message'] );
        }
        require_once 'partials/'.$this->plugin_name.'-admin-settings-display.php';
    }

    public function settingsPageSettingsMessages($error_message){
        switch ($error_message) {
            case '1':
                $message = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'my-text-domain' );                 $err_code = esc_attr( 'settings_page_example_setting' );                 $setting_field = 'settings_page_example_setting';
                break;
        }
        $type = 'error';
        add_settings_error(
            $setting_field,
            $err_code,
            $message,
            $type
        );
    }

    public function registerAndBuildFields() {
        /**
         * First, we add_settings_section. This is necessary since all future settings must belong to one.
         * Second, add_settings_field
         * Third, register_setting
         */
        add_settings_section(
        // ID used to identify this section and with which to register options
            'apisearch_settings',
            // Title to be displayed on the administration page
            'Apisearch Settings',
            // Callback used to render the description of the section
            array( $this, 'settings_page_display_general_account' ),
            // Page on which to add this section of options
            $this->plugin_name
        );

        add_settings_field(
            'apisearch_enabled',
            'Apisearch enabled',
            array( $this, 'setting_boolean' ),
            $this->plugin_name,
            'apisearch_settings',
            [
                'name' => 'apisearch_enabled'
            ]
        );

        add_settings_field(
            'apisearch_endpoint',
            'Apisearch server endpoint',
            array( $this, 'setting_text' ),
            $this->plugin_name,
            'apisearch_settings',
            [
                'name' => 'apisearch_endpoint'
            ]
        );

        add_settings_field(
            'apisearch_admin_endpoint',
            'Apisearch admin endpoint',
            array( $this, 'setting_text' ),
            $this->plugin_name,
            'apisearch_settings',
            [
                'name' => 'apisearch_admin_endpoint'
            ]
        );

        add_settings_field(
            'apisearch_app_id',
            'App ID',
            array( $this, 'setting_text' ),
            $this->plugin_name,
            'apisearch_settings',
            [
                'name' => 'apisearch_app_id'
            ]
        );

        add_settings_field(
            'apisearch_index_id',
            'Index ID',
            array( $this, 'setting_text' ),
            $this->plugin_name,
            'apisearch_settings',
            [
                'name' => 'apisearch_index_id'
            ]
        );

        add_settings_field(
            'apisearch_token_id',
            'Token ID',
            array( $this, 'setting_text' ),
            $this->plugin_name,
            'apisearch_settings',
            [
                'name' => 'apisearch_token_id'
            ]
        );

        register_setting($this->plugin_name, 'apisearch_endpoint');
        register_setting($this->plugin_name, 'apisearch_enabled');
        register_setting($this->plugin_name, 'apisearch_admin_endpoint');
        register_setting($this->plugin_name, 'apisearch_app_id');
        register_setting($this->plugin_name, 'apisearch_index_id');
        register_setting($this->plugin_name, 'apisearch_token_id');

    }
    public function settings_page_display_general_account() {
        echo '<p>These settings apply to Apisearch configuration.</p>';
    }

    public function setting_text($args) {
        $name = $args['name'];
        $val = get_option($name);
        echo "<p><input name='$name' value='$val'></p>";
    }

    public function setting_boolean($args) {
        $name = $args['name'];
        $val = get_option($name);
        ?>
        <fieldset>
            <label>
                <input type="radio" name="<?php echo $name ?>" value="true" <?php checked( $val, 'true' ); ?>>
                <?php _e( 'True', 'flowygo' ); ?>
            </label>
            <br>
            <label>
                <input type="radio" name="<?php echo $name ?>" value="false" <?php checked( $val, 'false' ); ?>>
                <?php _e( 'False', 'flowygo' ); ?>
            </label>
        </fieldset>
        <?php
    }
}
