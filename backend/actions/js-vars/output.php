<?php

// Full output
$output = '
// Environment variables for this Servant instance
(function (root) {
	var id = "servant";
	if (typeof root[id] === undefined) {
		var s = '.str_replace('\\/', '/', json_encode($constants)).';
		root[id] = s;
	}
})(window);
';
?>