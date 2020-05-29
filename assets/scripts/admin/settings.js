jQuery( document ).ready( function( $ ) {
	$.admin_tabs = {

		init : function() {
			$( 'a.nav-tab' ).click( function( e ) {
				var $this = $( this );

				e.preventDefault();

				$this.parents( '.nav-tab-wrapper:first' ).find( '.nav-tab-active' ).removeClass( 'nav-tab-active' );
				$this.addClass( 'nav-tab-active' );

				$( '.nav-container:visible' ).hide();

				var hash = $this.attr( 'href' );

				$( hash+'_tab' ).show();

				//fix the referer so if changes are saved, we come back to the same tab
				var referer = $( 'input[name=_wp_http_referer]' ).val();
				if ( referer.indexOf( '#' ) >= 0 ) {
					referer = referer.substr( 0, referer.indexOf( '#' ) );
				}
				referer += hash;

				window.location.hash = hash;

				$( 'input[name=_wp_http_referer]' ).val( referer );
			});

			if ( window.location.hash ) {
				$( 'a.nav-tab[href="' + window.location.hash + '"]' ).click();
			}

			return false;
		}

	}; //End of admin_tabs

	$.admin_tabs.init();
});

//
( function( FOOPEOPLE, $, undefined ) {

	FOOPEOPLE.loadImageOptimizationContent = function() {
		var data = 'action=foopeople_get_image_optimization_info' +
			'&_wpnonce=' + $( '#foopeople_setting_image_optimization-nonce' ).val() +
			'&_wp_http_referer=' + encodeURIComponent( $( 'input[name="_wp_http_referer"]' ).val() );

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: data,
			success: function( data ) {
				$( '#foopeople_settings_image_optimization_container' ).replaceWith( data );
			}
		});
	};

	FOOPEOPLE.bindClearCssOptimizationButton = function() {
		$( '.foopeople_clear_css_optimizations' ).click( function( e ) {
			var $button = $( this ),
				$container = $( '#foopeople_clear_css_optimizations_container' ),
				$spinner = $( '#foopeople_clear_css_cache_spinner' ),
				data = 'action=foopeople_clear_css_optimizations' +
				'&_wpnonce=' + $button.data( 'nonce' ) +
				'&_wp_http_referer=' + encodeURIComponent( $( 'input[name="_wp_http_referer"]' ).val() );

			e.preventDefault();

			$spinner.addClass( 'is-active' );
			$button.prop( 'disabled', true );

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: data,
				success: function( data ) {
					$container.html( data );
				},
				complete: function() {
					$spinner.removeClass( 'is-active' );
					$button.prop( 'disabled', false );
				}
			});
		});
	};

	FOOPEOPLE.bindTestThumbnailButton = function() {
		$( '.foopeople_thumb_generation_test' ).click( function( e ) {
			var $button = $( this ),
				$container = $( '#foopeople_thumb_generation_test_container' ),
				$spinner = $( '#foopeople_thumb_generation_test_spinner' ),
				data = 'action=foopeople_thumb_generation_test' +
					'&_wpnonce=' + $button.data( 'nonce' ) +
					'&_wp_http_referer=' + encodeURIComponent( $( 'input[name="_wp_http_referer"]' ).val() );

			e.preventDefault();

			$spinner.addClass( 'is-active' );
			$button.prop( 'disabled', true );

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: data,
				success: function( data ) {
					$container.html( data );
				},
				complete: function() {
					$spinner.removeClass( 'is-active' );
					$button.prop( 'disabled', false );
				}
			});
		});
	};

	FOOPEOPLE.bindApplyRetinaDefaults = function() {
		$( '.foopeople_apply_retina_support' ).click( function( e ) {
			var $button = $( this ),
				$container = $( '#foopeople_apply_retina_support_container' ),
				$spinner = $( '#foopeople_apply_retina_support_spinner' ),
				data = 'action=foopeople_apply_retina_defaults' +
					'&_wpnonce=' + $button.data( 'nonce' ) +
					'&_wp_http_referer=' + encodeURIComponent( $( 'input[name="_wp_http_referer"]' ).val() );

			var selected = [];

			e.preventDefault();

			$( $button.data( 'inputs' ) ).each( function() {
				if ( $( this ).is( ':checked' ) ) {
					selected.push( $( this ).attr( 'name' ) );
				}
			});

			data += '&defaults=' + selected;

			$spinner.addClass( 'is-active' );
			$button.prop( 'disabled', true );

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: data,
				success: function( data ) {
					$container.html( data );
				},
				complete: function() {
					$spinner.removeClass( 'is-active' );
					$button.prop( 'disabled', false );
				}
			});
		});
	};

	FOOPEOPLE.bindUninstallButton = function() {
		$( '.foopeople_uninstall' ).click( function( e ) {
			var $button = $( this ),
				$container = $( '#foopeople_uninstall_container' ),
				$spinner = $( '#foopeople_uninstall_spinner' ),
				data = 'action=foopeople_uninstall' +
					'&_wpnonce=' + $button.data( 'nonce' ) +
					'&_wp_http_referer=' + encodeURIComponent( $( 'input[name="_wp_http_referer"]' ).val() );

			e.preventDefault();

			$spinner.addClass( 'is-active' );
			$button.prop( 'disabled', true );

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: data,
				success: function( data ) {
					$container.html( data );
				},
				complete: function() {
					$spinner.removeClass( 'is-active' );
					$button.prop( 'disabled', false );
				}
			});
		});
	};

	FOOPEOPLE.bindClearHTMLCacheButton = function() {
		$( '.foopeople_clear_html_cache' ).click( function( e ) {
			var $button = $( this ),
				$container = $( '#foopeople_clear_html_cache_container' ),
				$spinner = $( '#foopeople_clear_html_cache_spinner' ),
				data = 'action=foopeople_clear_html_cache' +
					'&_wpnonce=' + $button.data( 'nonce' ) +
					'&_wp_http_referer=' + encodeURIComponent( $( 'input[name="_wp_http_referer"]' ).val() );

			e.preventDefault();

			$spinner.addClass( 'is-active' );
			$button.prop( 'disabled', true );

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: data,
				success: function( data ) {
					$container.html( data );
				},
				complete: function() {
					$spinner.removeClass( 'is-active' );
					$button.prop( 'disabled', false );
				}
			});
		});
	};

	//find all generic foopeople ajax buttons and bind them
	FOOPEOPLE.bindSettingsAjaxButtons = function () {
		$( '.foopeople_settings_ajax' ).click( function( e ) {
			var $button = $( this ),
				$container = $button.parents( '.foopeople_settings_ajax_container:first' ),
				$spinner = $container.find( '.spinner' ),
				response = $button.data( 'response' ),
				confirmMessage = $button.data( 'confirm' ),
				confirmResult = true,
				data = 'action=' + $button.data( 'action' ) +
					'&_wpnonce=' + $button.data( 'nonce' ) +
					'&_wp_http_referer=' + encodeURIComponent( $( 'input[name="_wp_http_referer"]' ).val() );

			e.preventDefault();

			if ( confirmMessage ) {
				confirmResult = confirm( confirmMessage );
			}

			if ( confirmResult ) {
				$spinner.addClass( 'is-active' );
				$button.prop( 'disabled', true );

				$.ajax({
					type    : 'POST',
					url     : ajaxurl,
					data    : data,
					success : function ( data ) {
						if ( response === 'replace_container' ) {
							$container.html( data );
						} else if ( response === 'alert' ) {
							alert( data );
						}
					},
					complete: function () {
						$spinner.removeClass( 'is-active' );
						$button.prop( 'disabled', false );
					}
				});
			}
		});
	};

	$( function() { //wait for ready
		FOOPEOPLE.loadImageOptimizationContent();
		FOOPEOPLE.bindClearCssOptimizationButton();
		FOOPEOPLE.bindTestThumbnailButton();
		FOOPEOPLE.bindApplyRetinaDefaults();
		FOOPEOPLE.bindUninstallButton();
		FOOPEOPLE.bindClearHTMLCacheButton();

		FOOPEOPLE.bindSettingsAjaxButtons();
	});

}( window.FOOPEOPLE = window.FOOPEOPLE || {}, jQuery ) );