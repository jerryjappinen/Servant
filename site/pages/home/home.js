
$(document).ready(function() {

	// Change title link behavior in menu
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

	// Change title link behavior in menu
	var cloneurl = $('.cloneurl');
	cloneurl.on('click', function (event) {
		cloneurl.select();
	});

});
