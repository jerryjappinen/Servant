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
    $string = trim(preg_replace('/[^0-9\+\-\*\.\/\(\) ]/i', '', $string));
    $compute = create_function('', 'return ('.(empty($string) ? 0 : $string).');');
    $result = 0 + $compute();
    return $intval ? intval($result) : $result;
}



// Check if string starts with a specific substring
function starts_with ($subject, $substring) {
	$result = false;
	$substringLength = strlen($substring);
	if (strlen($subject) >= $substringLength and substr($subject, 0, $substringLength) === $substring) {
		$result = true;
	}
	return $result;
}

// Check if string ends with a specific substring
function ends_with ($subject, $substring) {
	$result = false;
	$substringLength = strlen($substring);
	if (strlen($subject) >= $substringLength and substr($subject, -($substringLength)) === $substring) {
		$result = true;
	}
	return $result;
}



// Make sure initial characters of a string are what they need to be
function start_with ($subject, $substring = '') {
	if (!starts_with($subject, $substring)) {
		$subject = $substring.$subject;
	}
	return $subject;
}

// Make sure initial characters of a string are NOT what they shouldn't to be
function dont_start_with ($subject, $substring = '', $onlyCheckOnce = false) {
	if (starts_with($subject, $substring)) {

		// Cut the substring out
		$subject = substr($subject, strlen($substring));
		if ($subject === false) {
			$subject = '';
		}

		// Make sure that the new string still doesn't start with the substring
		if (!$onlyCheckOnce) {
			$subject = dont_start_with($subject, $substring);
		}

	}
	return $subject;
}

// Make sure final characters of a string are what they need to be
function end_with ($subject, $substring = '') {
	if (!ends_with($subject, $substring)) {
		$subject .= $substring;
	}
	return $subject;
}

// Make sure final characters of a string are NOT what they shouldn't to be
function dont_end_with ($subject, $substring = '', $onlyCheckOnce = false) {
	if (ends_with($subject, $substring)) {

		// Cut the substring out
		$subject = substr($subject, 0, -(strlen($substring)));

		// Make sure that the new string still doesn't start with the substring
		if (!$onlyCheckOnce) {
			$subject = dont_end_with($subject, $substring);
		}

	}
	return $subject;
}

// Trim excess whitespaces, empty lines etc. from a string.
function trim_text ($subject) {
	if (is_string($subject)) {
		return preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n\n", trim($subject)));
	} else {
		return $subject;
	}
}

?>