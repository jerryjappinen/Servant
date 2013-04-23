<?php

// Decodes a string into an array
// NOTE format: "key:value,anotherKey:value;nextSetOfValues;lastSetA,lastSetB"
// FLAG only used once, should be a private method in input
function parse_into_array ($string) {

	$result = array();

	// Iterate through all the values/key-value pairs
	foreach (explode(';', $string) as $key => $value) {

		// Individual value
		if (strpos($value, ',') === false and strpos($value, ':') === false) {
			$result[$key] = trim($value);

		// List
		} else {
			foreach (explode(',', $value) as $key2 => $value2) {

				$value2 = trim($value2, '"');

				// Key-value pair
				if (strpos($value2, ':') !== false) {
					$temp2 = explode(':', $value2);
					$result[$key][$temp2[0]] = $temp2[1];

				// Plain value
				} else {
					$result[$key][$key2] = $value2;
				}

			}
		}
	}

	// FLAG I'm looping the results twice
	foreach ($result as $key => $value) {
		if (is_string($value) and empty($value)) {
			unset($result[$key]);
		}
	}

	return $result;
}

?>