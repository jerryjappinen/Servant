<?php

// Create page tree in HTML
// FLAG this is not the way to do this... (in more than one way)
if (!function_exists('createNestedLists')) {
	function createNestedLists ($servant, $map) {
		$result = '';
		foreach ($map as $id => $value) {

			// Children
			if (is_array($value)) {
				$result .= '<li>'.$id.createNestedLists($servant, $value).'</li>';

			// Pages
			} else {
				$result .= '<li>'.$value->name().'</li>';
			}

		}
		return '<ol>'.$result.'</ol>';
	}
}
?>