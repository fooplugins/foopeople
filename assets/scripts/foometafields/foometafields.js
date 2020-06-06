( function( FOOMETAFIELDS, $, undefined ) {

	FOOMETAFIELDS.moveTaxonomyBoxes = function() {
		var taxonomyBoxes = FOOMETAFIELDS.getTaxonomyMetaBoxes();

		$.each( taxonomyBoxes, function( key, value ) {
			$( value.metabox ).detach().appendTo( '.foometafields-content[data-name="' + value.panel + '"]' );
		});
	};

	FOOMETAFIELDS.getTaxonomyMetaBoxes = function() {
		var taxonomies = [];

		$( '#poststuff' ).find( '.foometafields-tab[data-taxonomy]' ).each( function() {
			var $el = $( this ),
				data = $el.data();

			taxonomies.push({
				panel: data.name,
				metabox: '#' + data.taxonomy
			});
		});

		return taxonomies;
	};

	FOOMETAFIELDS.bindTabEvents = function() {
		$( '#poststuff' ).on( 'click', '.foometafields-tab', function( e ) {
			var $this 		= $( this ),
				$settings 	= $this.closest( '.foometafields-container' ),
				name 		= $this.data( 'name' );

			e.preventDefault();

			$settings.find( '.foometafields-active' ).removeClass( 'foometafields-active' );
			$settings.find( '[data-name="' + name + '"]' ).addClass( 'foometafields-active' );
		});
	};

	FOOMETAFIELDS.movePortraitBox = function() {
		$( '#poststuff' ).find( '.foometafields-content[data-feature-image]' ).each( function() {
			$( '#postimagediv' ).removeClass( 'closed' ).detach().appendTo( this );
		});
	};

	FOOMETAFIELDS.setupAutoSuggestFields = function() {
		$( '#poststuff' ).find( 'input[data-suggest]' ).each( function() {
			$( this ).suggest(
				window.ajaxurl + '?' + $( this ).data( 'suggest-query' ),
				{
					multiple: $( this ).data( 'suggest-multiple' ),
					multipleSep: $( this ).data( 'suggest-separator' )
				}
			);
		});
	};

	FOOMETAFIELDS.setupColorpickerFields = function() {
		$( '#poststuff input[data-wp-color-picker]' ).wpColorPicker();
	};

	$( function() { //wait for ready
		FOOMETAFIELDS.moveTaxonomyBoxes();
		FOOMETAFIELDS.movePortraitBox();
		FOOMETAFIELDS.bindTabEvents();
		FOOMETAFIELDS.setupAutoSuggestFields();
		FOOMETAFIELDS.setupColorpickerFields();
	});

}( window.FOOMETAFIELDS = window.FOOMETAFIELDS || {}, jQuery ) );
