
window.addEventListener('keydown', function (event) {

	// Catch key
	if (window.event) { // eg. IE
		keynum = window.event.keyCode;
	} else if (event.which) { // eg. Firefox
		keynum = event.which;
	}

	// Toggle layout with space key
	if (keynum == 13) {
		var sidebar = document.getElementById('sidebar');
		if (sidebar.className === '') {
			sidebar.className = 'open';
		} else {
			sidebar.className = '';
		}
	}

}, false);
