
dom.doc.ready(function () {

	// Mark scrolled document
	var treatScroll = function () {
		if (dom.doc.scrollTop() < 1) {
			dom.body.removeClass('scrolled');
		} else {
			dom.body.addClass('scrolled');
		}
	};



	// Bind
	treatScroll();
	dom.doc.on('scroll touchmove', function (event) {
		treatScroll();
	});
	dom.doc.ready(function (event) {
		treatScroll();
	});



	// Page scrolling links
	$('a.scroll').click(function (event) {
		event.preventDefault();

		// Detect target
		var link = $(this);
		var target = link.attr('data-target');
		if (!target) {
			target = link.attr('href');
		}

		$('html, body').animate({
			scrollTop: $(target).offset().top
		}, 600);

	});



	// External links
	dom.body.on('click', 'a', function (event) {
		var link = $(this);
		if (link.attr('href') && link.attr('target') !== '_blank' && !link.hasClass('internal') && link[0].hostname !== location.hostname) {
			link.attr('target', '_blank');
		}
	});



	// Initializations
    FastClick.attach(document.body);

}, false);
