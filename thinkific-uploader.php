<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://thinkific.com
 * @since             1.0.0
 * @package           Thinkific_Uploader
 *
 * @wordpress-plugin
 * Plugin Name:       Thinkific Uploader
 * Description:       Upload your WordPress content to your Thinkific course as a text lesson or insert a Checkout URL in your pages & posts.
 * Version:           1.0.0
 * Author:            Thinkific
 * Author URI:        https://www.thinkific.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       thinkific-uploader
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-thinkific-uploader-activator.php
 */
function activate_thinkific_uploader()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-thinkific-uploader-activator.php';
    Thinkific_Uploader_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-thinkific-uploader-deactivator.php
 */
function deactivate_thinkific_uploader()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-thinkific-uploader-deactivator.php';
    Thinkific_Uploader_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_thinkific_uploader');
register_deactivation_hook(__FILE__, 'deactivate_thinkific_uploader');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-thinkific-uploader.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_thinkific_uploader()
{
    $plugin = new Thinkific_Uploader();
    $plugin->run();
}
run_thinkific_uploader();
