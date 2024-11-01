<?php

/**
 *
 * @link       http://thinkific.com
 * @since      1.0.0
 *
 * @package    Thinkific_Uploader
 * @subpackage Thinkific_Uploader/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Thinkific_Uploader
 * @subpackage Thinkific_Uploader/includes
 * @author     Thinkific <https://www.thinkific.com>
 */
class Thinkific_Uploader
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Thinkific_Uploader_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = 'thinkific-uploader';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Thinkific_Uploader_Loader. Orchestrates the hooks of the plugin.
     * - Thinkific_Uploader_i18n. Defines internationalization functionality.
     * - Thinkific_Uploader_Admin. Defines all hooks for the admin area.
     * - Thinkific_Uploader_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-thinkific-uploader-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-thinkific-uploader-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-thinkific-uploader-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-thinkific-uploader-public.php';

        $this->loader = new Thinkific_Uploader_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Thinkific_Uploader_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Thinkific_Uploader_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Thinkific_Uploader_Admin($this->get_plugin_name(), $this->get_version());

        // Add top-level menu item
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_dashboard_menu');

        // Add tools-menu item
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_tools_menu');

        // Add settings-menu item (has to be called before 'enqueue_scripts')
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

        // Add css
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');

        // Add javascript
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Add settings error messages
        $this->loader->add_action('admin_notices', $plugin_admin, 'thinkific_settings_error_admin_notices');

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');
        $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');

        // Save/Update our plugin options
        $this->loader->add_action('admin_init', $plugin_admin, 'options_update');

        // Register Ajax call for receiving the choosen post content
        $this->loader->add_action('wp_ajax_get_post_for_thinkific', $plugin_admin, 'get_post_for_thinkific');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Thinkific_Uploader_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // Add button to load 'Checkout URL' modal
        $this->loader->add_action('media_buttons', $plugin_public, 'add_thinkific_link_generator', 99);

        // Add button to post current page/post in 'Thinkific Uploader'
        $this->loader->add_action('media_buttons', $plugin_public, 'add_thinkific_uploader_button', 99);

        // Add 'Product link generator modal
        $this->loader->add_action('admin_footer', $plugin_public, 'display_plugin_course_checkout_link_generator', 100);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Thinkific_Uploader_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
