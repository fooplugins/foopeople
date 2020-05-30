( function( FOOPEOPLE, $, undefined ) {

	// FOOPEOPLE.moveTaxonomyBoxes = function() {
	// 	var taxonomyBoxes = {
	// 		teams: 			$("#taxonomy-pacepeople_department"),
	// 		skills: 		$("#taxonomy-pacepeople_skill"),
	// 		locations: 		$("#taxonomy-pacepeople_location")
	// 	};
	//
	// 	$.each(taxonomyBoxes, function(key, array) {
	// 		if (array[0] === '') return false;
	// 		$(array[0]).detach().appendTo('.pacepeople-tab-content[data-name="_pacepeople_person_details-' + key + '"]');
	// 	});
	// };

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
		$( '#postimagediv' ).detach().appendTo( '.foometafields-content[data-name="foopeople-person-details-portrait"]' );
	};

	$( function() { //wait for ready
		//FOOPEOPLE.moveTaxonomyBoxes();
		FOOPEOPLE.movePortraitBox();
		FOOPEOPLE.bindAdminEvents();
	});

}( window.FOOPEOPLE = window.FOOPEOPLE || {}, jQuery ) );
