jQuery(document).ready(function($) {
    $.admin_tabs = {
 
        init : function() {
          $("a.nav-tab").click( function(e) { 
              e.preventDefault(); 

              $this = $(this);

              $this.parents(".nav-tab-wrapper:first").find(".nav-tab-active").removeClass("nav-tab-active");
              $this.addClass("nav-tab-active");

              $(".nav-container:visible").hide();

              var hash = $this.attr("href");

              $(hash+'_tab').show();

              //fix the referer so if changes are saved, we come back to the same tab
              var referer = $("input[name=_wp_http_referer]").val();
              if (referer.indexOf("#") >= 0) {
                referer = referer.substr(0, referer.indexOf("#"));
              }
              referer += hash;

              window.location.hash = hash;

              $("input[name=_wp_http_referer]").val(referer);
          });

          if (window.location.hash) {
            $('a.nav-tab[href="' + window.location.hash + '"]').click();
          }

          return false;
        }

    }; //End of admin_tabs

    $.admin_tabs.init(); 
});

//
(function(PACEPEOPLE, $, undefined) {

    PACEPEOPLE.loadImageOptimizationContent = function() {
        var data = 'action=pacepeople_get_image_optimization_info' +
            '&_wpnonce=' + $('#pacepeople_setting_image_optimization-nonce').val() +
            '&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function(data) {
                $('#pacepeople_settings_image_optimization_container').replaceWith(data);
            }
        });
    };

    PACEPEOPLE.bindClearCssOptimizationButton = function() {
        $('.pacepeople_clear_css_optimizations').click(function(e) {
            e.preventDefault();

            var $button = $(this),
                $container = $('#pacepeople_clear_css_optimizations_container'),
                $spinner = $('#pacepeople_clear_css_cache_spinner'),
                data = 'action=pacepeople_clear_css_optimizations' +
                '&_wpnonce=' + $button.data('nonce') +
                '&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

            $spinner.addClass('is-active');
            $button.prop('disabled', true);

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                success: function(data) {
                    $container.html(data);
                },
                complete: function() {
                    $spinner.removeClass('is-active');
                    $button.prop('disabled', false);
                }
            });
        });
    };

    PACEPEOPLE.bindTestThumbnailButton = function() {
        $('.pacepeople_thumb_generation_test').click(function(e) {
            e.preventDefault();

            var $button = $(this),
                $container = $('#pacepeople_thumb_generation_test_container'),
                $spinner = $('#pacepeople_thumb_generation_test_spinner'),
                data = 'action=pacepeople_thumb_generation_test' +
                    '&_wpnonce=' + $button.data('nonce') +
                    '&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

            $spinner.addClass('is-active');
            $button.prop('disabled', true);

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                success: function(data) {
                    $container.html(data);
                },
                complete: function() {
                    $spinner.removeClass('is-active');
                    $button.prop('disabled', false);
                }
            });
        });
    };

    PACEPEOPLE.bindApplyRetinaDefaults = function() {
        $('.pacepeople_apply_retina_support').click(function(e) {
            e.preventDefault();

            var $button = $(this),
                $container = $('#pacepeople_apply_retina_support_container'),
                $spinner = $('#pacepeople_apply_retina_support_spinner'),
                data = 'action=pacepeople_apply_retina_defaults' +
                    '&_wpnonce=' + $button.data('nonce') +
                    '&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

            var selected = [];
            $( $button.data('inputs') ).each(function() {
                if ($(this).is(":checked")) {
                    selected.push($(this).attr('name'));
                }
            });

            data += '&defaults=' + selected;

            $spinner.addClass('is-active');
            $button.prop('disabled', true);

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                success: function(data) {
                    $container.html(data);
                },
                complete: function() {
                    $spinner.removeClass('is-active');
                    $button.prop('disabled', false);
                }
            });
        });
    };

    PACEPEOPLE.bindUninstallButton = function() {
        $('.pacepeople_uninstall').click(function(e) {
            e.preventDefault();

            var $button = $(this),
                $container = $('#pacepeople_uninstall_container'),
                $spinner = $('#pacepeople_uninstall_spinner'),
                data = 'action=pacepeople_uninstall' +
                    '&_wpnonce=' + $button.data('nonce') +
                    '&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

            $spinner.addClass('is-active');
            $button.prop('disabled', true);

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                success: function(data) {
                    $container.html(data);
                },
                complete: function() {
                    $spinner.removeClass('is-active');
                    $button.prop('disabled', false);
                }
            });
        });
    };

    PACEPEOPLE.bindClearHTMLCacheButton = function() {
        $('.pacepeople_clear_html_cache').click(function(e) {
            e.preventDefault();

            var $button = $(this),
                $container = $('#pacepeople_clear_html_cache_container'),
                $spinner = $('#pacepeople_clear_html_cache_spinner'),
                data = 'action=pacepeople_clear_html_cache' +
                    '&_wpnonce=' + $button.data('nonce') +
                    '&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

            $spinner.addClass('is-active');
            $button.prop('disabled', true);

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                success: function(data) {
                    $container.html(data);
                },
                complete: function() {
                    $spinner.removeClass('is-active');
                    $button.prop('disabled', false);
                }
            });
        });
    };

    //find all generic pacepeople ajax buttons and bind them
    PACEPEOPLE.bindSettingsAjaxButtons = function () {
        $('.pacepeople_settings_ajax').click(function(e) {
            e.preventDefault();

            var $button = $(this),
                $container = $button.parents('.pacepeople_settings_ajax_container:first'),
                $spinner = $container.find('.spinner'),
                response = $button.data('response'),
                confirmMessage = $button.data('confirm'),
                confirmResult = true,
                data = 'action=' + $button.data('action') +
                    '&_wpnonce=' + $button.data('nonce') +
                    '&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

            if ( confirmMessage ) {
                confirmResult = confirm( confirmMessage );
            }

            if ( confirmResult ) {
                $spinner.addClass('is-active');
                $button.prop('disabled', true);

                $.ajax({
                    type    : "POST",
                    url     : ajaxurl,
                    data    : data,
                    success : function (data) {
                        if (response === 'replace_container') {
                            $container.html(data);
                        } else if (response === 'alert') {
                            alert(data);
                        }
                    },
                    complete: function () {
                        $spinner.removeClass('is-active');
                        $button.prop('disabled', false);
                    }
                });
            }
        });
    };

    $(function() { //wait for ready
        PACEPEOPLE.loadImageOptimizationContent();
        PACEPEOPLE.bindClearCssOptimizationButton();
        PACEPEOPLE.bindTestThumbnailButton();
        PACEPEOPLE.bindApplyRetinaDefaults();
        PACEPEOPLE.bindUninstallButton();
        PACEPEOPLE.bindClearHTMLCacheButton();

        PACEPEOPLE.bindSettingsAjaxButtons();
    });

}(window.PACEPEOPLE = window.PACEPEOPLE || {}, jQuery));