( function( FOOPEOPLE, $, undefined ) {

	FOOPEOPLE.moveTaxonomyBoxes = function() {
		var taxonomyBoxes = FOOPEOPLE.getTaxonomyMetaBoxes();

		$.each( taxonomyBoxes, function( key, value ) {
			$( value.metabox ).detach().appendTo( '.foometafields-content[data-name="' + value.panel + '"]' );
		});
	};

	FOOPEOPLE.getTaxonomyMetaBoxes = function() {
		var taxonomies = [];

		$( '#poststuff' ).find( '.foometafields-tab[data-taxonomy]' ).each( function() {
			var $el = $( this ),
				data = $el.data(),
				item = new Object();

			item.panel = data.name;
			item.metabox = '#' + data.taxonomy;

			taxonomies.push( item );
		});

		return taxonomies;
	};

	FOOPEOPLE.bindAdminEvents = function() {
		$( '#poststuff' ).on( 'click', '.foometafields-tab', function( e ) {
			var $this 		= $( this ),
				$settings 	= $this.closest( '.foometafields-container' ),
				name 		= $this.data( 'name' );

			e.preventDefault();

			$settings.find( '.foometafields-active' ).removeClass( 'foometafields-active' );
			$settings.find( '[data-name="' + name + '"]' ).addClass( 'foometafields-active' );
		});
	};

	FOOPEOPLE.movePortraitBox = function() {
		$( '#poststuff' ).find( '.foometafields-content[data-feature-image]' ).each( function() {
			$( '#postimagediv' ).removeClass('closed').detach().appendTo( this );
		});
	};

	$( function() { //wait for ready
		FOOPEOPLE.moveTaxonomyBoxes();
		FOOPEOPLE.movePortraitBox();
		FOOPEOPLE.bindAdminEvents();
	});

}( window.FOOPEOPLE = window.FOOPEOPLE || {}, jQuery ) );
