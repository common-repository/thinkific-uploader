(function($) {
  "use strict";
  var items = null;

  $(document).ready(function() {
    preloadThinkificCourses();
    populateWordpressContent();
    $(".select2").select2({
      width: "resolve" // need to override the changed default
    });
  });

  $(function() {
    $("#thnk_wp_content").change(function(e) {
      preloadThinkificCourses();
      populateWordpressContent();
    });
    $("#thnk_courses").change(function(e) {
      var course_id = this.value;
      if (!isNaN(course_id)) {
        clearChapterSelect();
        populateThinkificCourseChapters(course_id);
      }
    });
    $("#thnk_send_to_course").on("click", function(e) {
      e.preventDefault();
      postWordpressContentToThinkific();
    });
  });

  function preloadThinkificCourses() {
    var subdomain = php_variables.subdomain;
    var api_key = php_variables.api_key;
    var origin = php_variables.origin;

    $.ajax({
      type: "GET",
      url: "https://api.thinkific.com/api/wordpress/v1/courses/",
      contentType: "application/json; charset=utf-8",
      headers: {
        "X-Auth-API-Key": api_key,
        "X-Auth-Subdomain": subdomain,
        "X-Requested-By": origin
      },
      success: function(resultData) {
        items = resultData.sort(function(a, b) {
          return compareItems(a, b, "name");
        });
        populateThinkificCourses();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert(php_variables.thinkific_connection_error);
        window.location.href = "admin.php?page=thinkific-settings";
      }
    });
  }

  function populateWordpressContent() {
    var post_id = thnk_wp_content.value;

    if (!isNaN(post_id)) {
      $.ajax({
        url: ajaxurl,
        data: {
          action: "get_post_for_thinkific",
          post_id: post_id
        },
        success: function(data) {
          var $response = $(data);
          var postdata = $response.filter("#postdata").html();
          $("#single-post-container").html(postdata);
          $("#thnk_courses").prop("disabled", false);
          $("#thnk_chapters").prop("disabled", false);
          $("#thnk_free_trial").prop("disabled", false);
          $("#thnk_make_draft").prop("disabled", false);
          $("#thnk_send_to_course").prop("disabled", false);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert(php_variables.thinkific_get_wp_error);
        }
      });
    }
  }

  function populateThinkificCourses() {
    clearCourseSelect();
    $.each(items, function(key, value) {
      $("#thnk_courses").append(
        $("<option>", {
          value: value.id,
          text: value.name
        })
      );
    });
  }

  function populateThinkificCourseChapters(course_id) {
    course_id = parseInt(course_id);
    var course = items.find(function(item) {
      return item.id === course_id;
    });
    if (course) {
      clearChapterSelect();
      for (var i = 0; i < course.chapters.length; i++) {
        var chapter = course.chapters[i];
        $("#thnk_chapters").append(
          $("<option>", {
            value: chapter.id,
            text: chapter.name
          })
        );
      }
      $("#thnk_chapters")
        .find("option")
        .not(":disabled")
        .eq(0)
        .prop("selected", true);
    }
  }

  function postWordpressContentToThinkific() {
    var subdomain = php_variables.subdomain;
    var api_key = php_variables.api_key;
    var origin = php_variables.origin;
    var chapter_id = thnk_chapters.value;
    var data = {
      chapter_id: chapter_id,
      name: $("#thnk_wp_content")
        .find("option:selected")
        .text(),
      free: thnk_free_trial.checked,
      draft: thnk_make_draft.checked,
      html_text: $("#single-post-container").html()
    };

    if (!isNaN(chapter_id)) {
      $.ajax({
        type: "POST",
        url: "https://api.thinkific.com/api/wordpress/v1/html_text_contents",
        dataType: "json",
        headers: {
          "X-Auth-API-Key": api_key,
          "X-Auth-Subdomain": subdomain,
          "X-Requested-By": origin
        },
        data: data,
        success: function(resultData) {
          clearCourseSelect();
          clearChapterSelect();
          $("#thnk_wp_content")
            .find('option[disabled="disabled"]')
            .prop("selected", true);
          $("#thnk_courses").prop("disabled", true);
          $("#thnk_chapters").prop("disabled", true);
          $("#thnk_free_trial").prop("checked", false);
          $("#thnk_make_draft").prop("checked", false);
          $("#thnk_send_to_course").prop("disabled", false);
          alert(php_variables.thinkific_post_success);
          location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert(php_variables.thinkific_post_error);
          window.location.href = "admin.php?page=thinkific-settings";
        }
      });
    } else {
      alert(php_variables.thinkific_post_warning);
    }
  }

  // Helpers
  function clearCourseSelect() {
    $("#thnk_courses").empty();
    $("#thnk_courses").append(
      $("<option>", {
        disabled: true,
        hidden: true,
        selected: true,
        value: null,
        text: php_variables.thinkific_course_placeholder_text
      })
    );
  }

  function clearChapterSelect() {
    $("#thnk_chapters").empty();
    $("#thnk_chapters").append(
      $("<option>", {
        disabled: true,
        hidden: true,
        selected: true,
        value: null,
        text: php_variables.thinkific_chapter_placeholder_text
      })
    );
  }

  function clearInput(elem) {
    $(elem).val("");
  }

  function compareItems(itemA, itemB, propertyName) {
    if (itemA[propertyName] < itemB[propertyName]) {
      return -1;
    }
    if (itemA[propertyName] > itemB[propertyName]) {
      return 1;
    }
    return 0;
  }
})(jQuery);
