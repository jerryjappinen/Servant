<?php

// Create article tree in HTML
if (!function_exists('createNestedLists')) {
	function createNestedLists ($servant, $array) {
		$result = '';
		foreach ($array as $key => $value) {

			// Children
			// FLAG doesn't detect arrays with only one item (these should be presented as individual articles)
			if (is_array($value)) {
				$result .= '<li>'.$servant->format()->name($key).createNestedLists($servant, $value).'</li>';

			// Articles
			} else {
				$result .= '<li>'.$servant->format()->name($key).'</li>';
			}

		}
		return '<ol>'.$result.'</ol>';
	}
}

?>