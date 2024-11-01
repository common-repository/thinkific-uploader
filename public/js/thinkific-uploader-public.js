(function($) {
  "use strict";
  var items = null;

  $(document).ready(function() {
    preloadThinkificProducts();
    $(".select2").select2({
      width: "resolve" // need to override the changed default
    });
    $('.button[data-toggle="thnk-modal"]').click(function(e) {
      e.preventDefault();
      openThinkificModal();
    });
    $('.thnk-button[data-dismiss="thnk-modal"], .thnk-modal-backdrop').click(
      function(e) {
        closeThinkificModal();
      }
    );
    $(".thnk-button--copy-link").on("click", function(e) {
      e.preventDefault();
      copyThinkificCheckoutUrlToClipboard();
    });
    $(".thnk-button--insert-link").on("click", function(e) {
      e.preventDefault();
      insertThinkificCheckoutLink();
    });
  });

  $(function() {
    $("#thnk_product").change(function(e) {
      var product_id = this.value;
      if (!isNaN(product_id)) {
        clearPriceSelect();
        clearInput("#thnk_product_url");
        $("#thnk_product_price").prop("disabled", false);
        populateThinkificProductPrices(product_id);
      }
    });
    $("#thnk_product_price").change(function(e) {
      var price_id = this.value;
      var selectedOptions = $(this.selectedOptions[0]);
      if (!isNaN(price_id)) {
        populateThinkificCheckoutDetails(selectedOptions);
      } else {
        clearInput("#thnk_product_url");
      }
    });
    $("#thnk_product_title, #thnk_product_url").on(
      "blur change keydown keypress keyup",
      function(e) {
        if (!$(this).val()) {
          $("#thnk_insert_link").prop("disabled", true);
        } else if (
          $(this)
            .val()
            .trim().length > 0
        ) {
          $("#thnk_insert_link").prop("disabled", false);
        }
      }
    );
  });

  function clearForm() {
    clearProductSelect();
    clearPriceSelect();
    clearInput("#thnk_product_title");
    clearInput("#thnk_product_url");
    $("#thnk_product_new_window").prop("checked", false);
  }

  function clearProductSelect() {
    $("#thnk_product").empty();
    $("#thnk_product").append(
      $("<option>", {
        disabled: true,
        hidden: true,
        selected: true,
        value: null,
        text: php_variables.thinkific_product_placeholder_text
      })
    );
  }

  function clearPriceSelect() {
    $("#thnk_product_price").empty();
    $("#thnk_product_price").append(
      $("<option>", {
        disabled: true,
        hidden: true,
        selected: true,
        value: null,
        text: php_variables.thinkific_price_placeholder_text
      })
    );
  }

  function clearInput(elem) {
    $(elem).val("");
  }

  function closeThinkificModal() {
    clearForm();
    $("body").removeClass("thnk-modal-open");
  }

  function openThinkificModal() {
    preloadThinkificProducts();
    $("body").addClass("thnk-modal-open");
  }

  function preloadThinkificProducts() {
    var subdomain = php_variables.subdomain;
    var api_key = php_variables.api_key;
    var origin = php_variables.origin;

    $.ajax({
      type: "GET",
      url: "https://api.thinkific.com/api/wordpress/v1/products/",
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
        populateThinkificProducts();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert(php_variables.thinkific_connection_error);
        window.location.href = "admin.php?page=thinkific-settings";
      }
    });
  }

  function populateThinkificProducts() {
    clearProductSelect();
    $.each(items, function(key, value) {
      $("#thnk_product").append(
        $("<option>", {
          value: value.id,
          text: value.name
        })
      );
    });
  }

  function populateThinkificProductPrices(product_id) {
    product_id = parseInt(product_id);
    var product = items.find(function(item) {
      return item.id === product_id;
    });
    if (product) {
      var prices = product.product_prices.sort(function(a, b) {
        return compareItems(a, b, "price_name");
      });
      for (var i = 0; i < product.product_prices.length; i++) {
        var price = product.product_prices[i];
        $("#thnk_product_price").append(
          $("<option>", {
            value: price.id,
            text: price.price_name,
            "data-title": price.price_name,
            "data-url": price.checkout_url
          })
        );
      }
      var regularPrice = $("#thnk_product_price")
        .find("option")
        .not(":disabled")
        .eq(0);
      regularPrice.prop("selected", true);
      var selectedOptions = regularPrice;
      populateThinkificCheckoutDetails(selectedOptions);
    }
  }

  function populateThinkificCheckoutDetails(selectedOptions) {
    $("#thnk_product_title").prop("disabled", false);
    $("#thnk_product_url").prop("disabled", false);
    $("#thnk_product_url").prop("readonly", true);
    $("#thnk_product_new_window").prop("disabled", false);
    $(".thnk-button--insert-link").prop("disabled", false);
    populateThinkificProductCheckoutTitle(selectedOptions.data("title"));
    populateThinkficProductCheckoutUrl(selectedOptions.data("url"));
  }

  function populateThinkificProductCheckoutTitle(title) {
    $("#thnk_product_title").val(title);
  }

  function populateThinkficProductCheckoutUrl(url) {
    $("#thnk_product_url").val(url);
  }

  function copyThinkificCheckoutUrlToClipboard() {
    var productUrl = $("#thnk_product_url");
    productUrl.select();
    document.execCommand("Copy");
  }

  function createThinkificCheckoutLink() {
    var title = $("#thnk_product_title").val(),
      url = $("#thnk_product_url").val(),
      newWindow = $("#thnk_product_new_window").prop("checked"),
      checkoutUrl = "",
      target = "";

    if (newWindow) {
      target = ' target="_blank"';
    }
    checkoutUrl = '<a href="' + url + '"' + target + ">" + title + "</a>";
    return checkoutUrl;
  }

  function insertThinkificCheckoutLink() {
    // Text editor
    if ($("#wp-content-wrap").hasClass("html-active")) {
      insertAtCaret("content", createThinkificCheckoutLink());
      clearForm();
    } else {
      // Visual editor
      var activeEditor = tinyMCE.get("content"),
        editorContent = activeEditor.getContent();
      if (activeEditor !== null) {
        activeEditor.execCommand(
          "mceInsertContent",
          false,
          createThinkificCheckoutLink()
        );
        clearForm();
      }
    }
    closeThinkificModal();
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

  function insertAtCaret(areaId, text) {
    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br =
      txtarea.selectionStart || txtarea.selectionStart == "0"
        ? "ff"
        : document.selection ? "ie" : false;
    if (br == "ie") {
      txtarea.focus();
      var range = document.selection.createRange();
      range.moveStart("character", -txtarea.value.length);
      strPos = range.text.length;
    } else if (br == "ff") {
      strPos = txtarea.selectionStart;
    }

    var front = txtarea.value.substring(0, strPos);
    var back = txtarea.value.substring(strPos, txtarea.value.length);
    txtarea.value = front + text + back;
    strPos = strPos + text.length;
    if (br == "ie") {
      txtarea.focus();
      var range = document.selection.createRange();
      range.moveStart("character", -txtarea.value.length);
      range.moveStart("character", strPos);
      range.moveEnd("character", 0);
      range.select();
    } else if (br == "ff") {
      txtarea.selectionStart = strPos;
      txtarea.selectionEnd = strPos;
      txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
  }
})(jQuery);
