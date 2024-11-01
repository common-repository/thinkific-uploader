<div class="thnk thnk-plugin-display-container">
  <div class="thnk-title-bar thnk-title-bar--bordered">
    <h1 class="thnk-h1"><?php echo esc_html(get_admin_page_title()); ?></h1>
  </div>

<?php
  if (isset($_GET['post_id'])) {
      $sending_post = $_GET['post_id'];
  } else {
      $sending_post = false;
  }
?>

  <div class="row">
   <div class="col col-md-12 col-lg-5">
    <form style="margin-bottom:40px;">
      <div class="form-row">
        <div class="form-group col-md-12">
          <label class="label" for="thnk_wp_content">
            <span><?php echo __('Choose Page or Post', $this->plugin_name)?></span>
          </label>
          <br>
          <select class="select2" name="thnk_wp_content" id="thnk_wp_content">
            <option disabled selected hidden><?php echo __('Please choose a Page or Post', $this->plugin_name)?></option>
            <optgroup label="Pages">
              <?php
                    global $post;
                    $args = array(
                        'numberposts' => null,
                        'posts_per_page' => -1,
                        'post_status' => array('draft', 'publish'),
                        'post_type' => 'page',
                        'order' => 'ASC',
                        'orderby' => 'title'
                    );
                    $posts = get_posts($args);
                    foreach ($posts as $post) : setup_postdata($post);
                ?>
                <option <?php if ($post->ID == $sending_post) {
                    echo "selected";
                } ?> value="<?php echo $post->ID; ?>">
            			<?php the_title(); ?>
            		</option>
              <?php endforeach; ?>
            </optgroup>
            <optgroup label="Posts">
              <?php
                    global $post;
                    $args = array(
                        'numberposts' => null,
                        'posts_per_page' => -1,
                        'post_status' => array('draft', 'publish'),
                        'post_type' => 'post',
                        'order' => 'ASC',
                        'orderby' => 'title'
                    );
                    $posts = get_posts($args);
                    foreach ($posts as $post) : setup_postdata($post);
                ?>
                <option <?php if ($post->ID == $sending_post) {
                    echo "selected";
                } ?> value="<?php echo $post->ID; ?>">
            			<?php the_title(); ?>
            		</option>
              <?php endforeach; ?>
            </optgroup>
          </select>
          <span id="external-source-help" class="form-text">
            This will be used to create a lesson.
          </span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-12">
          <label class="label" for="thnk_courses">
            <span><?php echo __('Choose a course within Thinkific', $this->plugin_name)?></span>
          </label>
          <br>
          <select disabled class="select2 form-control" name="thnk_courses" id="thnk_courses">
            <option disabled selected hidden><?php echo __('Please choose a course', $this->plugin_name)?></option>
          </select>
          <span id="external-source-help" class="form-text">
            The course where you want to send your post.
          </span>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label class="label" for="thnk_chapters">
            <span><?php echo __('Choose the course chapter', $this->plugin_name)?></span>
          </label>
          <br>
          <select disabled class="select2 form-control" name="thnk_chapters" id="thnk_chapters">
            <option disabled selected hidden><?php echo __('Please choose a chapter', $this->plugin_name)?></option>
          </select>
          <span id="external-source-help" class="form-text">
            The chapter where you want to send your post.
          </span>
        </div>
      </div>
      <div class="form-group">
        <div class="custom-control custom-checkbox">
          <input disabled type="checkbox" class="custom-control-input" name="thnk_free_trial" id="thnk_free_trial">
          <label class="custom-control-label" for="thnk_free_trial">
            <span><?php echo __('Make this part of the free trial', $this->plugin_name)?></span>
          </label>
        </div>
      </div>
      <div class="form-group">
        <div class="custom-control custom-checkbox">
          <input disabled type="checkbox" class="custom-control-input" name="thnk_make_draft" id="thnk_make_draft">
          <label class="custom-control-label" for="thnk_make_draft">
            <span><?php echo __('Set to draft', $this->plugin_name)?></span>
          </label>
        </div>
      </div>
      <input disabled type="submit" name="submit" class="thnk-button--primary thnk-button" id="thnk_send_to_course" value="<?php echo __('Send to Thinkific', $this->plugin_name)?>"/>
    </form>
   </div>
   <div class="col col-md-12 col-lg-7">
        <span class="label">
          Preview of post content to be sent to Thinkific
        </span>
        <span class="form-text">
          Shortcodes and other custom plugin content is not rendered before being uploaded.
          <br />Additional editing may be required to format this information properly once in Thinkific.
        </span>
        <div id="single-post-container" style="display: block;"></div>
   </div>
  </div>
</div>
