<div class="thnk thnk-modal" id="thinkific-course-checkout-link-thnk-modal" tabindex="-1" role="dialog" aria-labelledby="Thinkific product link generator" aria-hidden="true">
  <div class="thnk-modal-backdrop"></div>
  <div class="thnk-modal-dialog" role="document">
    <div class="thnk-modal-content">
      <div class="thnk-modal-header">
        <h3 class="thnk-modal-title">Link to course or bundle</h3>
        <button type="reset" class="thnk-button thnk-button--icon-only" data-dismiss="thnk-modal" aria-label="Close">
          <i class="toga-icon toga-icon-x"></i>
        </button>
      </div>
      <div class="thnk-modal-body">
        <form class="thnk-plugin-form-container">
          <div class="form-row thnk-form-product-select">
            <div class="form-group col-md-6">
              <label class="label" for="thnk_product">
                <?php echo __('Select course or bundle', $this->plugin_name) ?>
              </label>
              <br>
              <select class="select2 form-control" name="thnk_product" id="thnk_product">
                <option disabled selected hidden><?php echo __('Please choose a course or bundle', $this->plugin_name)?></option>
              </select>
              <span id="external-source-help" class="form-text">
                <?php echo __('The course or bundle you intend to link to', $this->plugin_name) ?>
              </span>
            </div>
            <div class="form-group col-md-6">
              <label class="label" for="thnk_product_price">
                <?php echo __('Select price', $this->plugin_name) ?>
              </label>
              <br>
              <select disabled class="select2 form-control" name="thnk_product_price" id="thnk_product_price">
                <option disabled selected hidden><?php echo __('Please choose a price', $this->plugin_name)?></option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-sm-12">
              <label class="label" for="thnk_product_title">
                <?php echo __('Link text', $this->plugin_name) ?>
              </label>
              <input disabled type="thnk_product_title" class="form-control" id="thnk_product_title" placeholder="<?php echo __('Please choose a course or bundle', $this->plugin_name) ?>">
            </div>
            <div class="form-group col-sm-12">
              <label class="label" for="thnk_product_url">
                <?php echo __('Link URL', $this->plugin_name) ?>
              </label>
              <input disabled type="thnk_product_url" class="form-control" id="thnk_product_url" placeholder="<?php echo __('Please choose a course or bundle', $this->plugin_name) ?>">
              <span id="external-source-help" class="form-text">
                <?php echo __('Checkout URL will only work with a published course or bundle', $this->plugin_name) ?>
              </span>
            </div>
            <div class="form-group col-sm-12">
              <div class="custom-control custom-checkbox">
                <input disabled type="checkbox" class="custom-control-input" id="thnk_product_new_window">
                <label class="custom-control-label" for="thnk_product_new_window">
                  <span><?php echo __('Open link in new window or tab', $this->plugin_name)?></span>
                </label>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="thnk-modal-footer">
        <div class="thnk-modal-copy-link form-text">
          <button type="button" class="thnk-button thnk-button--icon-only thnk-button--copy-link" data-clipboard="copy" aria-label="<?php echo __('Copy link to clipboard', $this->plugin_name) ?>">
            <i class="toga-icon toga-icon-link"></i>
          </button>
          <span class="hidden-sm"><?php echo __('or just&nbsp;', $this->plugin_name) ?></span>
          <a href class="thnk-button--copy-link" data-clipboard="copy"><?php echo __('copy link to clipboard', $this->plugin_name) ?></a>
        </div>
        <button type="reset" class="thnk-button--secondary thnk-button" data-dismiss="thnk-modal"><?php echo __('Cancel', $this->plugin_name)?></button>
        <button disabled type="button" id="thnk_insert_link" class="thnk-button--primary thnk-button thnk-button--insert-link"><?php echo __('Insert link', $this->plugin_name)?></button>
      </div>
    </div>
  </div>
</div>
