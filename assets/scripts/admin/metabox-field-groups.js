( function( FOOPEOPLE, $, undefined ) {

	FOOPEOPLE.moveTaxonomyBoxes = function() {
		var taxonomyBoxes = {
			teams: 			$( '#taxonomy-foopeople-team' ),
			skills: 		$( '#taxonomy-foopeople-skill' ),
			locations: 		$( '#taxonomy-foopeople-location' )
		};

		$.each( taxonomyBoxes, function( key, array ) {
			if ( '' === array[0] ) return false;
			$( array[0]).detach().appendTo( '.foopeople-tab-content[data-name="_foopeople_person_details-' + key + '"]' );
		});
	};

	FOOPEOPLE.bindAdminEvents = function() {
		$( '#poststuff' ).on( 'click', '.foopeople-vertical-tab', function( e ) {
			var $this 		= $( this ),
				$settings 	= $this.closest( '.foopeople-fields-container' ),
				name 		= $this.data( 'name' );

			e.preventDefault();

			$settings.find( '.foopeople-tab-active' ).removeClass( 'foopeople-tab-active' );
			$settings.find( '[data-name="' + name + '"]' ).addClass( 'foopeople-tab-active' );
		});
	};

	FOOPEOPLE.movePortraitBox = function() {
		$( '#postimagediv' ).detach().appendTo( '.foopeople-tab-content[data-name="_foopeople_person_details-portrait"]' );
	};

	$( function() { //wait for ready
		FOOPEOPLE.moveTaxonomyBoxes();
		FOOPEOPLE.movePortraitBox();
		FOOPEOPLE.bindAdminEvents();
	});

}( window.FOOPEOPLE = window.FOOPEOPLE || {}, jQuery ) );
