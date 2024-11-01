<?php

/**
    * The public-facing functionality of the plugin.
    *
    * @link       http://thinkific.com
    * @since      1.0.0
    *
    * @package    Thinkific_Uploader
    * @subpackage Thinkific_Uploader/public
    */

/**
    * The public-facing functionality of the plugin.
    *
    * Defines the plugin name, version, and two examples hooks for how to
    * enqueue the admin-specific stylesheet and JavaScript.
    *
    * @package    Thinkific_Uploader
    * @subpackage Thinkific_Uploader/public
    * @author     Thinkific <https://www.thinkific.com>
    */
class Thinkific_Uploader_Public
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
      * @param      string    $plugin_name       The name of the plugin.
      * @param      string    $version    The version of this plugin.
      */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public static function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
        * Register the JavaScript for the public-facing side of the site.
        *
        * @since    1.0.0
        */

    public function enqueue_scripts($hook)
    {
        // Load javascript only on posts & pages
        if ($hook != 'post.php' && $hook != 'post-new.php') {
            return;
        }

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/thinkific-uploader-public.js', array( 'jquery' ), $this->version, false);

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
            'thinkific_product_placeholder_text' => __('Please choose a course or bundle', $this->plugin_name),
            'thinkific_price_placeholder_text' => __('Please choose a price', $this->plugin_name),
            'thinkific_connection_error' => __('Something went wrong connecting to your Thinkific site. Make sure your settings are correct.', $this->plugin_name)
        );
        wp_localize_script($this->plugin_name, 'php_variables', $parameter_array);

        // Enqueued script with localized data.
        wp_enqueue_script($this->plugin_name);
    }

    public function add_thinkific_link_generator()
    {
        echo '<a href="#" class="button" id="thinkific-course-checkout-link" data-toggle="thnk-modal" data-target="#thinkific-course-checkout-link-thnk-modal">' . __('Insert Thinkific Checkout Link', $this->plugin_name) . '</a>';
    }

    public function add_thinkific_uploader_button()
    {
        global $post;
        $url = add_query_arg(
            array(
                'page' => $this->plugin_name,
                'post_id' => $post->ID
            ),
            admin_url('admin.php')
        );
        echo '<a href="' . $url . '" id="thinkific-uploader" class="button" >' . __('Post to Thinkific', $this->plugin_name) . '</a>';
    }

    /**
        * Render the course link generator modal
        *
        * @since    1.0.0
        */

    public function display_plugin_course_checkout_link_generator()
    {
        include_once('partials/thinkific-uploader-modal-display.php');
    }
}
