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
		FOOPEOPLE.bindSettingsAjaxButtons();
	});

}( window.FOOPEOPLE = window.FOOPEOPLE || {}, jQuery ) );
