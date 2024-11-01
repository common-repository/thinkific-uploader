<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://thinkific.com
 * @since      1.0.0
 *
 * @package    Thinkific_Uploader
 * @subpackage Thinkific_Uploader/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Thinkific_Uploader
 * @subpackage Thinkific_Uploader/includes
 * @author     Thinkific <https://www.thinkific.com>
 */
class Thinkific_Uploader_Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        delete_option('thinkific-uploader');
    }
}
