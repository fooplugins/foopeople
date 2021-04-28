"use strict";

jQuery(document).ready(function ($) {
  $.admin_tabs = {
    init: function init() {
      $('a.nav-tab').click(function (e) {
        var $this = $(this);
        e.preventDefault();
        $this.parents('.nav-tab-wrapper:first').find('.nav-tab-active').removeClass('nav-tab-active');
        $this.addClass('nav-tab-active');
        $('.nav-container:visible').hide();
        var hash = $this.attr('href');
        $(hash + '_tab').show(); //fix the referer so if changes are saved, we come back to the same tab

        var referer = $('input[name=_wp_http_referer]').val();

        if (referer.indexOf('#') >= 0) {
          referer = referer.substr(0, referer.indexOf('#'));
        }

        referer += hash;
        window.location.hash = hash;
        $('input[name=_wp_http_referer]').val(referer);
      });

      if (window.location.hash) {
        $('a.nav-tab[href="' + window.location.hash + '"]').click();
      }

      return false;
    }
  }; //End of admin_tabs

  $.admin_tabs.init();
}); //

(function (FOOPEOPLE, $, undefined) {
  //find all generic foopeople ajax buttons and bind them
  FOOPEOPLE.bindSettingsAjaxButtons = function () {
    $('.foopeople_settings_ajax').click(function (e) {
      var $button = $(this),
          $container = $button.parents('.foopeople_settings_ajax_container:first'),
          $spinner = $container.find('.spinner'),
          response = $button.data('response'),
          confirmMessage = $button.data('confirm'),
          confirmResult = true,
          data = 'action=' + $button.data('action') + '&_wpnonce=' + $button.data('nonce') + '&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());
      e.preventDefault();

      if (confirmMessage) {
        confirmResult = confirm(confirmMessage);
      }

      if (confirmResult) {
        $spinner.addClass('is-active');
        $button.prop('disabled', true);
        $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: data,
          success: function success(data) {
            if (response === 'replace_container') {
              $container.html(data);
            } else if (response === 'alert') {
              alert(data);
            }
          },
          complete: function complete() {
            $spinner.removeClass('is-active');
            $button.prop('disabled', false);
          }
        });
      }
    });
  };

  $(function () {
    //wait for ready
    FOOPEOPLE.bindSettingsAjaxButtons();
  });
})(window.FOOPEOPLE = window.FOOPEOPLE || {}, jQuery);
"use strict";

(function (FOOPEOPLE, $, undefined) {
  FOOPEOPLE.updateCache = function () {
    FOOPEOPLE.cache = {
      $textFields: $('#foopeople-person-details input[name^=\'foopeople-person-details\']'),
      $selectFields: $('#foopeople-person-details select[name^=\'foopeople-person-details\']'),
      $nodes: $('[data-pace-people-value]'),
      $portrait: $('#set-post-thumbnail'),
      $removePortrait: $('#remove-post-thumbnail'),
      portraitDefault: $('#ppl__portrait_thumbnail').data('defaultSrc')
    };
  };

  FOOPEOPLE.updatePortrait = function () {
    var $img = FOOPEOPLE.cache.$portrait.children('img');

    if (0 < $img.length) {
      $('#ppl__portrait_thumbnail').attr('src', $img.attr('src'));
    }
  };

  FOOPEOPLE.updateSelectValues = function () {
    FOOPEOPLE.selectFields = {
      locations: [],
      team: [],
      role: []
    };
    FOOPEOPLE.cache.$selectFields.each(function () {
      var $el = $(this),
          name = $el.attr('name'),
          value = $el.val(),
          textValues = '';
      $el.find('option:selected').each(function () {
        textValues += $(this).text() + ' ';
      });

      switch (name) {
        case 'foopeople-person-details[location][]':
          FOOPEOPLE.selectFields.locations.push(textValues);
          break;

        case 'foopeople-person-details[role][]':
          FOOPEOPLE.selectFields.role.push(textValues);
          break;

        case 'foopeople-person-details[team][]':
          FOOPEOPLE.selectFields.team.push(textValues);
          break;
      }
    });
  };

  FOOPEOPLE.updateTextboxValues = function () {
    FOOPEOPLE.textFields = {
      firstname: [],
      surname: [],
      preferred: [],
      jobtitle: [],
      phonenumber: [],
      email: []
    };
    FOOPEOPLE.cache.$textFields.each(function () {
      var $el = $(this),
          name = $el.attr('name'),
          value = $el.val();

      switch (name) {
        case 'foopeople-person-details[firstname]':
          FOOPEOPLE.textFields.firstname.push(value);
          break;

        case 'foopeople-person-details[surname]':
          FOOPEOPLE.textFields.surname.push(value);
          break;

        case 'foopeople-person-details[preferred]':
          FOOPEOPLE.textFields.preferred.push(value);
          break;

        case 'foopeople-person-details[jobtitle]':
          FOOPEOPLE.textFields.jobtitle.push(value);
          break;

        case 'foopeople-person-details[workmobile]':
          FOOPEOPLE.textFields.phonenumber.push(value);
          break;

        case 'foopeople-person-details[workemail]':
          FOOPEOPLE.textFields.email.push(value);
          break;
      }
    });
    FOOPEOPLE.updateTitleValue();
  };

  FOOPEOPLE.updateHTMLValues = function (values) {
    $.each(values, function (key, array) {
      var text = '',
          $field = $('[data-pace-people-value="' + key + '"]');

      if (0 !== array.length) {
        $(array).each(function (index, property) {
          console.log(key, property);

          if ('preferred' === key && 0 < property.length) {
            // Add Brackets for preferred name / nickname
            property = '(' + property + ')';
          }

          text += '<span class="ppl__item_delimiter">' + property + '</span> ';
        });
      } else {
        text = '<span class="ppl__item_delimiter ppl_text_captitalize">' + key + '</span> ';
      }

      $field.html(text);
    });
  };

  FOOPEOPLE.updateTitleValue = function () {
    $('#post_name').val('');
    $('#title').val(FOOPEOPLE.textFields.firstname + ' ' + FOOPEOPLE.textFields.surname);
  };

  FOOPEOPLE.showPreview = function () {
    $('#post-ppl-preview').removeClass('js-ppl__loading');
  };

  FOOPEOPLE.setThumbnailFromAjax = function (event, xhr, settings) {
    if ('string' === typeof settings.data && /action=get-post-thumbnail-html/.test(settings.data) && xhr.responseJSON && 'string' === typeof xhr.responseJSON.data) {
      FOOPEOPLE.updateCache(); // Update our cache, to handle newly created elements ie Thumbnail image

      FOOPEOPLE.updatePortrait();
      FOOPEOPLE.bindEvents(); // We have to bind events again, because of new elements created after AJAX request ie remove featured image button/link
    }
  };

  FOOPEOPLE.bindEvents = function () {
    FOOPEOPLE.cache.$removePortrait.on('click', function () {
      $('#ppl__portrait_thumbnail').attr('src', FOOPEOPLE.cache.portraitDefault);
    });
    FOOPEOPLE.cache.$selectFields.on('change', function () {
      FOOPEOPLE.updateSelectValues();
      FOOPEOPLE.updateHTMLValues(FOOPEOPLE.selectFields);
    });
    FOOPEOPLE.cache.$textFields.on('input', function () {
      FOOPEOPLE.updateTextboxValues();
      FOOPEOPLE.updateHTMLValues(FOOPEOPLE.textFields);
    });
    $(document).ajaxComplete(function (event, xhr, settings) {
      FOOPEOPLE.setThumbnailFromAjax(event, xhr, settings);
    });
  };

  $(function () {
    //wait for ready
    FOOPEOPLE.updateCache();
    FOOPEOPLE.updatePortrait();
    FOOPEOPLE.bindEvents();
    FOOPEOPLE.updateTextboxValues();
    FOOPEOPLE.updateSelectValues();
    FOOPEOPLE.updateHTMLValues(FOOPEOPLE.textFields);
    FOOPEOPLE.updateHTMLValues(FOOPEOPLE.selectFields);
    FOOPEOPLE.updateTitleValue();
    FOOPEOPLE.showPreview();
  });
})(window.FOOPEOPLE = window.FOOPEOPLE || {}, jQuery);