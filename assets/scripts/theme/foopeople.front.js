( function( FOOPEOPLE, $, undefined ) {


	FOOPEOPLE.updateCache = function() {
		FOOPEOPLE.cache = {
			wrapper: $( '.js-foopeople' ),
			listing: $( '.ppl_listing' )
		};
		FOOPEOPLE.orginal_url = window.location.pathname;
	};

	FOOPEOPLE.bindEvents = function() {
		FOOPEOPLE.cache.wrapper.on( 'click', '.ppl__listing-item', function() {

			var $el = $( this ),
				url = $el.find( '.ppl__card_details' ).data( 'url' );

			$el.siblings().removeClass( 'is-active' );

			if ( $el.hasClass( 'is-active' ) ) {
				$el.removeClass( 'is-active' );
				history.replaceState( null, null, FOOPEOPLE.orginal_url );
			} else {
				$el.addClass( 'is-active' );
				history.replaceState( null, null, url );
			}
		});

		FOOPEOPLE.cache.wrapper.on( 'input', '.js-foopeople-search', function() {
			var $el = $( this ),
				value = $el.val().toLowerCase();

			if ( 3 <= value.length ) {
				$( '.ppl__listing-item' ).addClass( 'is-hidden' );
				FOOPEOPLE.cache.listing.find( '.ppl__listing-item' ).each( function() {
					var $el = $( this ),
						searchString = $el.data( 'search' );

					if ( -1 !== searchString.indexOf( value ) ) {
						$el.removeClass( 'is-hidden' );
					}
				});
			} else {
				$( '.ppl__listing-item' ).removeClass( 'is-hidden' );
			}
		});


	};

	$( function() { //wait for ready
		FOOPEOPLE.updateCache();
		FOOPEOPLE.bindEvents();
	});

}( window.FOOPEOPLE = window.FOOPEOPLE || {}, jQuery ) );
