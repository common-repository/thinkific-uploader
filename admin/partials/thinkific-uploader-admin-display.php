<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://thinkific.com
 * @since      1.0.0
 *
 * @package    Thinkific_Uploader
 * @subpackage Thinkific_Uploader/admin/partials
 */
?>

<div class="thnk thnk-plugin-display-container">
  <div class="thnk-title-bar thnk-title-bar--bordered">
    <h1 class="thnk-h1"><?php echo esc_html(get_admin_page_title()); ?></h1>
  </div>

  <form method="post" name="thinkific_options" action="options.php">
  <?php
    $options = get_option($this->plugin_name);
    if ($options) {
        $thinkific_subdomain = $options['thinkific_subdomain'];
        $thinkific_api = $options['thinkific_api'];
    }
    settings_fields($this->plugin_name);
    do_settings_sections($this->plugin_name);
  ?>

  <div class="form-row">
    <div class="form-group col-md-6">
        <label class="label" for="<?php echo $this->plugin_name; ?>-thinkific_api">
          <span><?php echo __('Thinkific API key:', $this->plugin_name); ?></span>
        </label>
          <input type="text" class="form-control" id="<?php echo $this->plugin_name; ?>-thinkific_api" name="<?php echo $this->plugin_name; ?>[thinkific_api]" placeholder="API key" aria-label="Enter your Thinkific API key here" value="<?php if (!empty($thinkific_api)) {
      echo $thinkific_api;
  } ?>"/>
          <span id="external-source-help" class="form-text">
              Enter your Thinkific API key here.
          </span>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
        <label class="label" for="<?php echo $this->plugin_name; ?>-thinkific_subdomain">
          <span><?php echo __('Thinkific subdomain:', $this->plugin_name); ?></span>
        </label>
        <div class="input-group">
          <input  type="text" class="form-control" id="<?php echo $this->plugin_name; ?>-thinkific_subdomain" name="<?php echo $this->plugin_name; ?>[thinkific_subdomain]" placeholder="E.g. my-school" aria-label="Enter your Thinkific subdomain" value="<?php if (!empty($thinkific_subdomain)) {
      echo $thinkific_subdomain;
  } ?>"/>
              <div class="input-group-append">
                <span class="input-group-text">.thinkific.com</span>
              </div>
        </div>
        <span id="external-source-help" class="form-text">
            Enter your Thinkific subdomain.
        </span>
      </div>
    </div>

  <input type="hidden" id="<?php echo $this->plugin_name; ?>-thinkific_connected" name="<?php echo $this->plugin_name; ?>[thinkific_connected]" value="<?php if (!empty($thinkific_connected)) {
      echo $thinkific_connected;
  } ?>"/>
    <input type="submit" name="submit" id="submit" class="thnk-button--primary thnk-button" value="Save Changes"  />
  </form>
</div>
