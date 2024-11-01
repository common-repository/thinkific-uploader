<?php

/**
 * Fired during plugin activation
 *
 * @link       http://thinkific.com
 * @since      1.0.0
 *
 * @package    Thinkific_Uploader
 * @subpackage Thinkific_Uploader/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Thinkific_Uploader
 * @subpackage Thinkific_Uploader/includes
 * @author     Thinkific <https://www.thinkific.com>
 */
class Thinkific_Uploader_Activator
{

    /**
     * Adds Thinkific credentials to the db
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        add_option('thinkific-uploader');
    }
}
