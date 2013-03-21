<?php
// String functions

// Underscored to lower-camelcase 
function to_camelcase ($string) {
	return preg_replace('/ (.?)/e', 'strtoupper("$1")', strtolower($string)); 
}

// Camelcase to regular text 
function from_camelcase ($string) {
	return strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1 $2', $string)); 
}



// Do a calculation with a formula in a string
function calculate_string($string, $intval = false) {
    $string = trim(preg_replace('/[^0-9\+\-\*\/\(\) ]/i', '', $string));
    $compute = create_function('', 'return ('.(empty($string) ? 0 : $string).');');
    $result = 0 + $compute();
    return $intval ? intval($result) : $result;
}



// Make sure initial characters of a string are what they need to be
function start_with ($string, $start = '') {
	$startlength = strlen($start);
	if (strlen($string) < $startlength or substr($string, 0, $startlength) !== $start) {
		$string = $start.$string;
	}
	return $string;
}

// Make sure initial characters of a string are NOT what they shouldn't to be
function dont_start_with ($string, $start = '') {
	$startlength = strlen($start);
	if (strlen($string) >= $startlength and substr($string, 0, $startlength) === $start) {
		$string = substr($string, $startlength);
		$string = dont_start_with($string, $start);
	}
	return $string;
}

// Make sure final characters of a string are what they need to be
function end_with ($string, $end = '') {
	$endlength = strlen($end);
	if (strlen($string) < $endlength or substr($string, -($endlength)) !== $end) {
		$string .= $end;
	}
	return $string;
}

// Make sure final characters of a string are NOT what they shouldn't to be
function dont_end_with ($string, $end = '') {
	$endlength = strlen($end);
	if (strlen($string) >= $endlength and substr($string, -($endlength)) === $end) {
		$string = substr($string, 0, -($endlength));
		$string = dont_end_with($string, $end);
	}
	return $string;
}



// Gets the format of a file based on a filename
// FLAG
//    - too magical
//    - doesn't handle directories -> split functionality
//    - should NOT use $p
// ACCEPTS
// 		1. File name, path or extension
// 		2. Requested information
// 		 	type: 			returns the type of file (default)
// 			extension: 		returns the normalized file extension
function detect ($string, $mode = 'type') {

	global $p;

	// Parse file extension
	if (strrchr($string, '.')) {
		$extension = strtolower(pathinfo($string, PATHINFO_EXTENSION));
	} else {
		$extension = strtolower($string);
	}

	// Normalize duplicates
	$temp = array(
		'tiff' => 'tif',
		'jpeg' => 'jpg',
		'mpeg' => 'mpg',
		'htm' => 'html'
	);

	if (array_key_exists($extension, $temp)) {
		$extension = $temp[$extension];
	}

	// We're done if only extension was asked
	if ($mode == 'extension') {
		$return = empty($extension) ? false : $extension;

	// Apply valid categories
	} else {

		// Valid category
		foreach ($p->settings->fileTypes() as $key => $value) {
			if (in_array($extension, $value)) {
				$return = $key;
			}
		}

		// Last resort
		if (empty($return)) {
			$return = 'file';
		}

	}

	return $return;
}



// Decodes a string into an array
// NOTE format: "key:value,anotherKey:value;nextSetOfValues;lastSetA,lastSetB"
function shorthand_decode ($string) {

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



// Serialize an array into non-human-readable strings
function array_serialize ($array) {
	$string = '';
	foreach ($array as $key => $value) {
		$string .= '.'.base64_encode(serialize($value));
	}
	return substr($string, 1);
}

// Unserialize an array back into a sane format
function array_unserialize ($string) {
	$result = array();

	// Exploding serialized data
	if (!empty($string)) {
		foreach (explode('.', $string) as $value) {
			$result[] = unserialize(base64_decode($value));
		}
	}

	return $result;
}

?>