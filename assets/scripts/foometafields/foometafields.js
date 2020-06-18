( function( FOOMETAFIELDS, $ ) {

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
		// eslint-disable-next-line no-unused-vars
		$( '#poststuff' ).on( 'click', '.foometafields-tab,.foometafields-child-tab', function( e ) {
			e.preventDefault();
			e.stopPropagation();

			// eslint-disable-next-line vars-on-top
			var $this = $( this ),
				name = $this.data( 'name' ),
				selector = '[data-name="' + name + '"]',
				$container 	= $this.closest( '.foometafields-container' ),
				$content = $container.find( '.foometafields-contents' ).find( selector ),
				$parent = $this.closest( '.foometafields-tab' ),
				$child = $parent.find( selector );

			// first remove old active classes
			$container.find( '.foometafields-active' ).removeClass( 'foometafields-active' );

			// now re-apply the active classes to the elements that need it
			$parent.add( $child ).add( $content ).addClass( 'foometafields-active' );
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
