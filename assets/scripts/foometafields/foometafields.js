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

	FOOMETAFIELDS.setupSelectizeFields = function() {
		$( '#poststuff' ).find( '[data-selectize-instance]' ).each( function() {
			var $this = $( this ),
				$display = $( '#' + $this.data( 'selectize-display' ) );

			$this.selectize({
				valueField: 'id',
				labelField: 'text',
				searchField: 'text',
				maxItems: 1,
				options: [],
				create: false,
				onChange: function( value ) {
					var instance = $this[0].selectize,
						selection = instance.getItem( value );
					$display.val( selection.text() );
				},
				load: function( query, callback ) {
					if ( ! query.length ) {
						return callback();
					}
					$.ajax({
						url: window.ajaxurl + '?' + $this.data( 'selectize-query' ),
						type: 'GET',
						data: {
							q: query
						},
						error: function() {
							callback();
						},
						success: function( res ) {
							console.log( res.results );
							callback( res.results );
						}
					});
				}
			});
		});
	};

	FOOMETAFIELDS.setupSelect2Fields = function() {
		$( '#poststuff' ).find( 'select[data-select2-instance]' ).each( function() {
			var $this = $( this );
			$this.selectWoo({
				ajax: {
					url: window.ajaxurl,
					data: function( params ) {
						return {
							q: params.term,
							action: $this.data( 'select2-action' ),
							nonce: $this.data( 'select2-nonce' ),
							// eslint-disable-next-line camelcase
							query_type: $this.data( 'select2-query-type' ),
							// eslint-disable-next-line camelcase
							query_data: $this.data( 'select2-query-data' )
						};
					}
				}
			});
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
		FOOMETAFIELDS.setupSelectizeFields();
		FOOMETAFIELDS.setupColorpickerFields();
	});

}( window.FOOMETAFIELDS = window.FOOMETAFIELDS || {}, jQuery ) );
