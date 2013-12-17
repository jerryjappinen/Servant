
var doc = $(document);
doc.ready(function () {

	// Page scroll links
	$('a.scroll').click(function (event) {
		event.preventDefault();

		var link = $(this);
		var target = $(link.attr('href'));

		$('html, body').animate({
			scrollTop: target.offset().top
		}, 600);

	});



	// Fixed menu
	var win = $(window);
	var body = $('body');

	// Mark scrolled document
	var treatScroll = function () {
		if (doc.scrollTop() < 1) {
			body.removeClass('scrolled');
		} else {
			body.addClass('scrolled');
		}
	};

	doc.scroll(function (event) {
		treatScroll();
	});
	doc.ready(function (event) {
		treatScroll();
	});



}, false);
