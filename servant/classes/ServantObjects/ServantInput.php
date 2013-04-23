<?php

class ServantInput extends ServantObject {

	// Properties
	protected $propertyAction 	= null;
	protected $propertyArticle 	= null;
	protected $propertySite 	= null;



	// Take input
	public function initialize ($input = null) {

		// Set defaults
		$this->setAction('')->setArticle(array())->setSite('');

		// Select things if we have any
		if (!empty($input) and is_array($input)) {

			// Look for whatever we support
			foreach (array('action', 'article', 'site') as $id) {

				// Call setter if user has provided input
				if (array_key_exists($id, $input)) {
					$this->callSetter($id, array($input[$id]));
				}

			}

		}

		return $this;
	}



	// Public getters
	public function action () {
		return $this->get('action');
	}
	public function site () {
		return $this->get('site');
	}



	// Setters

	protected function setAction ($value) {
		$result = '';
		if ($this->acceptString($value)) {
			$result = $this->normalizeString($value);
		}
		return $this->set('action', $result);
	}

	// List of article names to select one in the article tree
	protected function setArticle ($values) {
		$results = array();

		// Sanitize each item
		if (!empty($values)) {

			// Parse string input
			if (is_string($values)) {

				// Assume JSON
				$temp = json_decode($values);
				if (is_array($temp)) {
					$results = $temp;

				// Shorthand
				} else {
					$results = $this->parseIntoArray($values);
				}

			// Try to use it as an array
			} else {
				$values = to_array($values);
				ksort($values);

				// Use each individual item as string, with max 9 supported values as a fail safe
				// FLAG hardcoded maximum count
				$i = 0;
				foreach ($values as $value) {
					if ($this->acceptString($value) and $i < 9) {
						$results[] = $this->normalizeString($value);
					} else {
						break;
					}
					$i++;
				}

			}

		}

		return $this->set('article', $results);
	}

	protected function setSite ($value) {
		$result = '';
		if ($this->acceptString($value)) {
			$result = $this->normalizeString($value);
		}
		return $this->set('site', $result);
	}



	/**
	* Private helpers
	*/

	/**
	* Is the value good enough to accept after sanitizing?
	*/
	private function acceptString ($value) {
		return !empty($value) and (is_string($value) or is_int($value));
	}

	/**
	* Normalize slightly malformed input
	*/
	private function normalizeString ($value) {
		return trim(strval($value));
	}

	/**
	* Decodes a string into an array (probably from GET)
	*
	* NOTE
	*   - format: "key:value,anotherKey:value;nextSetOfValues;lastSetA,lastSetB"
	*/
	private function parseIntoArray ($string) {

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

}

?>