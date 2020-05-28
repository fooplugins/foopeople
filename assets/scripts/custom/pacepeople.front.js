(function (PACEPEOPLE, $, undefined) {

	PACEPEOPLE.updateCache = function () {
		PACEPEOPLE.cache = {
            wrapper : $('.js-pacepeople'),
            listing : $('.ppl_listing')
		};
    };    
    
    PACEPEOPLE.init = function() {
        orginal_url = window.location.pathname;
    };

    PACEPEOPLE.bindEvents = function() {
        PACEPEOPLE.cache.wrapper.on('click', '.ppl__listing-item', function() {
            var $el = $(this),
                url = $el.find('.ppl__card_details').data('url');

            $el.siblings().removeClass('is-active');

            if($el.hasClass('is-active')) {
                $el.removeClass('is-active');
                history.replaceState(null, null, orginal_url);
            } else {
                $el.addClass('is-active');
                history.replaceState(null, null, url);
            }
        });

        PACEPEOPLE.cache.wrapper.on('input', '.js-pacepeople-search', function() {
            var $el = $(this),
                value = $el.val().toLowerCase();

            if(value.length >= 3) {
                $('.ppl__listing-item').addClass('is-hidden');
                PACEPEOPLE.cache.listing.find('.ppl__listing-item').each( function() {
                    var $el = $(this), 
                        searchString = $el.data('search');

                    if( searchString.indexOf(value) !== -1 ) {
                        $el.removeClass('is-hidden')
                    }
                });
            } else {
                $('.ppl__listing-item').removeClass('is-hidden');
            }
        });


    };

    $(function () { //wait for ready

        PACEPEOPLE.updateCache();
        PACEPEOPLE.init();
        PACEPEOPLE.bindEvents();

        $("#ppl__org-chart").jOrgChart({
            chartElement: '#chart'
        });

	});

}(window.PACEPEOPLE = window.PACEPEOPLE || {}, jQuery));