(function (PACEPEOPLE, $, undefined) {

	PACEPEOPLE.updateCache = function () {
		PACEPEOPLE.cache = {
			$checkboxes: $("#poststuff input[name^='tax_input']"),
			$textFields : $("#pacepeople_person_details input[name^='_pacepeople_person_details']"),
			$nodes: $('[data-pace-people-value]'),
			$portrait: $('#set-post-thumbnail'),
			$removePortrait : $('#remove-post-thumbnail'),
			portraitDefault: $('#ppl__portrait_thumbnail').data('defaultSrc')
		};
	};

	PACEPEOPLE.updatePortrait = function() {		
		var $img = PACEPEOPLE.cache.$portrait.children('img');
		if($img.length > 0) {
			$('#ppl__portrait_thumbnail').attr('src', $img.attr('src') );
		}
	};

	PACEPEOPLE.updateCheckboxValues = function () {
		PACEPEOPLE.taxonomyFields = {
			department: [],
			location: [],
			skills: [],
			employeetype: []
		};

		PACEPEOPLE.cache.$checkboxes.each(function () {
			var $el = $(this),
				value = '',
				name = '';

			if ($el.is(':checked')) {

				value = $el[0].nextSibling.nodeValue; 
				name = $el[0].name;

				switch (name) {
					case 'tax_input[pacepeople_department][]':
						PACEPEOPLE.taxonomyFields.department.push(value);
						break;
					case 'tax_input[pacepeople_location][]':
						PACEPEOPLE.taxonomyFields.location.push(value);
						break;
					case 'tax_input[pacepeople_skill][]':
						PACEPEOPLE.taxonomyFields.skills.push(value);
						break;
				}
			}
		});
	};
	PACEPEOPLE.updateTextboxValues = function() {
		PACEPEOPLE.textFields = {
			firstname: [],
			surname: [],
			preferred: [],
			jobtitle: [],
			phonenumber: [],
			email: []
		};

		PACEPEOPLE.cache.$textFields.each(function () {
			var $el = $(this),
				value = '',
				name = '';

			value = $el[0].value; 
			name = $el[0].name;

			switch (name) {
				case '_pacepeople_person_details[firstname]':
					PACEPEOPLE.textFields.firstname.push(value);
					break;
				case '_pacepeople_person_details[surname]':
					PACEPEOPLE.textFields.surname.push(value);
					break;
				case '_pacepeople_person_details[preferred]':
					PACEPEOPLE.textFields.preferred.push(value);
					break;
				case '_pacepeople_person_details[jobtitle]':
					PACEPEOPLE.textFields.jobtitle.push(value);
					break;
				case '_pacepeople_person_details[phonenumber]':
					PACEPEOPLE.textFields.phonenumber.push(value);
					break;
				case '_pacepeople_person_details[email]':
					PACEPEOPLE.textFields.email.push(value);
					break;
			}
			
		});
		PACEPEOPLE.updateTitleValue(); 
	}


	
	PACEPEOPLE.updateHTMLValues = function (values) {
		$.each(values, function (key, array) {
			var text = '';

			if(array[0] === '') return false;

			if (array.length !== 0) {
				$(array).each(function (index, property) {
					text += '<span class="ppl__item_delimiter">' + property + '</span> ';
				});
			} else {
				text = '<span class="ppl__item_delimiter ppl_text_captitalize">' + key + '</span> ';
			}
			$('[data-pace-people-value="' + key + '"]').html(text);
		});
	};


	PACEPEOPLE.updateTitleValue = function() {
		$('#post_name').val('');
		$('#title').val(PACEPEOPLE.textFields.firstname + ' ' + PACEPEOPLE.textFields.surname);
	}


	PACEPEOPLE.showPreview = function () {
		$("#post-ppl-preview").removeClass("js-ppl__loading");
	};

	PACEPEOPLE.setThumbnailFromAjax = function (event, xhr, settings) {
		if (typeof settings.data === 'string'
			&& /action=get-post-thumbnail-html/.test(settings.data)
			&& xhr.responseJSON
			&& typeof xhr.responseJSON.data === 'string'
		) {
			PACEPEOPLE.updateCache(); // Update our cache, to handle newly created elements ie Thumbnail image
			PACEPEOPLE.updatePortrait();
			PACEPEOPLE.bindEvents(); // We have to bind events again, because of new elements created after AJAX request ie remove featured image button/link
		}
	};

	PACEPEOPLE.bindEvents = function() {
		PACEPEOPLE.cache.$removePortrait.on('click', function () {
			$('#ppl__portrait_thumbnail').attr('src', PACEPEOPLE.cache.portraitDefault );
		});		

		PACEPEOPLE.cache.$checkboxes.on('change', function () {
			PACEPEOPLE.updateCheckboxValues();
			PACEPEOPLE.updateHTMLValues(PACEPEOPLE.taxonomyFields);
		});

		PACEPEOPLE.cache.$textFields.on('input', function () {
			PACEPEOPLE.updateTextboxValues();
			PACEPEOPLE.updateHTMLValues(PACEPEOPLE.textFields);
		});
 
		$(document).ajaxComplete(function (event, xhr, settings) {
			PACEPEOPLE.setThumbnailFromAjax(event, xhr, settings);
		});
		
	};

	$(function () { //wait for ready
		PACEPEOPLE.updateCache();
		PACEPEOPLE.updatePortrait();

		PACEPEOPLE.bindEvents();

		PACEPEOPLE.updateCheckboxValues();
		PACEPEOPLE.updateTextboxValues();

		PACEPEOPLE.updateHTMLValues(PACEPEOPLE.taxonomyFields);
		PACEPEOPLE.updateHTMLValues(PACEPEOPLE.textFields);

		PACEPEOPLE.updateTitleValue(); 

		PACEPEOPLE.showPreview();

	});

}(window.PACEPEOPLE = window.PACEPEOPLE || {}, jQuery));