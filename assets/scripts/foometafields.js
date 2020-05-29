(function (PACEPEOPLE, $, undefined) {

	// PACEPEOPLE.moveTaxonomyBoxes = function() {
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

	PACEPEOPLE.bindAdminEvents = function() {
		$("#poststuff").on("click", ".foometafields-tab", function (e) {
			e.preventDefault();
			var $this 		= $(this),
				$settings 	= $this.closest(".foometafields-container"),
				name 		= $this.data("name");

			$settings.find(".foometafields-active").removeClass("foometafields-active");
			$settings.find('[data-name="' + name + '"]').addClass("foometafields-active");
		});
	};

	// PACEPEOPLE.movePortraitBox = function() {
	// 	$('#postimagediv').detach().appendTo('.pacepeople-tab-content[data-name="_pacepeople_person_details-portrait"]');
	// };

	$(function () { //wait for ready
		//PACEPEOPLE.moveTaxonomyBoxes();
		//PACEPEOPLE.movePortraitBox();
		PACEPEOPLE.bindAdminEvents();
	});

}(window.PACEPEOPLE = window.PACEPEOPLE || {}, jQuery));
