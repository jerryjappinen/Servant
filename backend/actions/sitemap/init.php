<?php

// Create page tree in HTML
if (!function_exists('createNestedLists')) {
	function createNestedLists ($servant, $array) {
		$result = '';
		foreach ($array as $key => $value) {

			// Children
			// FLAG doesn't detect arrays with only one item (these should be presented as individual pages)
			if (is_array($value)) {
				$result .= '<li>'.$servant->format()->pageName($key).createNestedLists($servant, $value).'</li>';

			// Pages
			} else {
				$result .= '<li>'.$servant->format()->pageName($key).'</li>';
			}

		}
		return '<ol>'.$result.'</ol>';
	}
}

?>