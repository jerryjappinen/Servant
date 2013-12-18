
$(document).ready(function() {

	// Change title in menu
	var homeLink = $('.row-menu h1 a');

	homeLink.wrapInner('<span class="hide-over-break"></span>').prepend('<span class="hide-under-break">servantframework.com</span>');

	homeLink.on('click', function (event) {
		if (doc.scrollTop() > 0) {
			event.preventDefault();
			$('html, body').animate({
				scrollTop: $('body').offset().top
			}, 200);
		}
	});

});
