
// Page scrolling
$('a.scroll').click(function (event) {
	event.preventDefault();

	var link = $(this);
	var target = $(link.attr('href'));

	$('html, body').animate({
		scrollTop: target.offset().top
	}, 600);

});
