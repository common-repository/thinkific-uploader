<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://thinkific.com
 * @since      1.0.0
 *
 * @package    Thinkific_Uploader
 * @subpackage Thinkific_Uploader/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Thinkific_Uploader
 * @subpackage Thinkific_Uploader/admin
 * @author     Thinkific <https://www.thinkific.com>
 */
class Thinkific_Uploader_Admin
{

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
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/thinkific-uploader-admin.css', array(), $this->version, 'all');
        wp_enqueue_style('thinkific-uploader-google-fonts', 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700', false);
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */

    public function enqueue_scripts($hook)
    {
        // Load for all admin pages
        wp_enqueue_script('thinkific-select2', plugin_dir_url(__FILE__) . 'js/select2.min.js', array( 'jquery' ), $this->version, false);

        // Load javascript only on Thinkific uploader
        if ($hook != 'toplevel_page_thinkific-uploader') {
            return;
        }

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/thinkific-uploader-admin.js', array( 'jquery' ), $this->version, false);

        $thinkific_subdomain = '';
        $thinkific_api = '';

        $options = get_option($this->plugin_name);
        if ($options) {
            $thinkific_subdomain = $options['thinkific_subdomain'];
            $thinkific_api = $options['thinkific_api'];
        }

        $parameter_array = array(
      'subdomain' => $thinkific_subdomain,
      'api_key' => $thinkific_api,
      'origin' => $this->version.'-wp',
            'thinkific_course_placeholder_text' => __('Please choose a course', $this->plugin_name),
            'thinkific_chapter_placeholder_text' => __('Please choose a chapter', $this->plugin_name),
      'thinkific_connection_error' => __('Something went wrong connecting to your Thinkific site. Make sure your settings are correct.', $this->plugin_name),
      'thinkific_post_success' => __('Successfully posted your content to your Thinkific site!', $this->plugin_name),
      'thinkific_post_error' => __('There was a problem uploading your post to Thinkific.', $this->plugin_name),
      'thinkific_post_warning' => __('Please choose a course and a chapter to upload your content.', $this->plugin_name),
      'thinkific_get_wp_error' => __('There was a problem receiving your Wordpress content.', $this->plugin_name)
    );
        wp_localize_script($this->plugin_name, 'php_variables', $parameter_array);

        // Enqueued script with localized data.
        wp_enqueue_script($this->plugin_name);
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_dashboard_menu()
    {
        add_menu_page('Thinkific', 'Thinkific', 'manage_options', 'thinkific-uploader');
    }

    /**
      * Render the tools menu into the Wordpress Tools menu.
      *
      * @since    1.0.0
      */

    public function add_plugin_tools_menu()
    {
        add_submenu_page('thinkific-uploader', 'Thinkific Uploader', 'Thinkific Uploader', 'manage_options', 'thinkific-uploader', array($this, 'display_plugin_tools_page'));
    }

    /**
      * Render the error messages into the Wordpress Tools menu.
      *
      * @since    1.0.0
      */

    public function thinkific_settings_error_admin_notices()
    {
        settings_errors('thinkific_settings_error');
    }

    /**
        * Render the tools page
        *
        * @since    1.0.0
        */

    public function display_plugin_tools_page()
    {
        include_once('partials/thinkific-uploader-tools-display.php');
    }

    /**
      * Render the settings page for this plugin.
      *
      * @since    1.0.0
      */

    public function add_plugin_admin_menu()
    {
        add_submenu_page('thinkific-uploader', 'Thinkific Settings', 'Settings', 'manage_options', 'thinkific-settings', array($this, 'display_plugin_setup_page'));
    }

    /**
        * Render the settings page
        *
        * @since    1.0.0
        */

    public function display_plugin_setup_page()
    {
        include_once('partials/thinkific-uploader-admin-display.php');
    }

    /**
        * Add settings action link to the plugins page.
        *
        * @since    1.0.0
        */

    public function add_action_links($links)
    {
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=thinkific-settings') . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);
    }

    /**
        * Validate settings
        *
        * @since    1.0.0
        */

    public function validate($input)
    {
        $valid = array();
        $message = null;
        $type = null;
        $connected = false;

        if ($input['thinkific_api'] != null && $input['thinkific_subdomain'] != null) {
            $connected = $this->connect_to_thinkific($input);
            // var_dump($connected);
            if ($connected) {
                $type = 'updated';
                $message = __('Connection to Thinkific established and credentials saved.', $this->plugin_name);
            } else {
                $type = 'error';
                $message = __("Couldn't establish connection to Thinkific, please check your credentials.", $this->plugin_name);
            }
        } else {
            $type = 'error';
            $message = __("Input fields can't be empty. Please fill in your credentials.", $this->plugin_name);
        }

        $valid['thinkific_subdomain'] = $input['thinkific_subdomain'];
        $valid['thinkific_api'] = $input['thinkific_api'];
        $valid['thinkific_connected'] = $connected;

        add_settings_error(
      'thinkific_settings_error',
      esc_attr('settings_updated'),
      $message,
      $type
    );
        return $valid;
    }

    /**
       * Try to connect to the Tenants Thinkfic Site
       *
       * @since    1.0.0
     **/

    public function connect_to_thinkific($input)
    {
        $args = array(
        'headers' => array(
          'X-Auth-API-Key' => $input['thinkific_api'],
          'X-Auth-Subdomain' => $input['thinkific_subdomain'],
          'X-Requested-By' => $this->version.'-wp'
      )
    );
        $response = wp_remote_get('https://api.thinkific.com/api/wordpress/v1/products/', $args);
        $http_code = wp_remote_retrieve_response_code($response);
        if ($http_code == 200) {
            return true;
        }
        return false;
    }

    /**
        * Save settings after validating them
        * gets called from admin_init
        *
        * @since    1.0.0
        **/

    public function options_update()
    {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }

    /**
        * Get all Posts and Pages for the DDL
        *
        * @since    1.0.0
        **/

    public function get_post_for_thinkific()
    {
        $pid = intval($_REQUEST['post_id']);
        $args = array(
            'post_status' => 'any',
            'post_type'   => 'any',
            'p' => $pid
        );
        $the_query  = new WP_Query($args);

        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $data = '
          <div class="thinkific-post-container">
            <div class="thinkfic-entry-content">'.get_the_content().'</div>
	        </div>';
            }
        }
        wp_reset_postdata();
        echo '<div id="postdata">'.$data.'</div>';
        die();
    }
}
