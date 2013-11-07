<?php

/**
* Request input container
*/
class ServantInput extends ServantObject {



	/**
	* Properties
	*/
	protected $propertyAction 	= null;
	protected $propertyPage 	= null;



	/**
	* Take input
	*/
	public function initialize ($input = null) {

		// Set defaults to all properties
		$this->setAction('')->setPage(array());

		// Select things if we have any
		if (!empty($input) and is_array($input)) {

			// Accepting only GET input
			$input = $input['get'];

			// Look for whatever we support
			foreach (array('action', 'page') as $id) {

				// Call setter if user has provided input
				if (array_key_exists($id, $input)) {
					$this->callSetter($id, array($input[$id]));
				}

			}

		}

		return $this;
	}



	/**
	* Public getters
	*/
	public function action () {
		return $this->get('action');
	}
	public function page () {
		return $this->get('page');
	}



	/**
	* Setters
	*/

	/**
	* Action
	*/
	protected function setAction ($value) {
		$result = '';

		if ($this->acceptable($value)) {
			$result = $this->normalizeString($value);
		}


		// Silent fallback
		if (!$this->servant()->actions()->available($result)) {

			// Global default
			$default = $this->servant()->settings()->defaults('action');
			if ($this->servant()->actions()->available($default)) {
				$result = $default;

			// Whatever's available
			} else {
				$available = array_keys($this->servant()->actions->map());
				$result = $available[0];
			}
		}

		return $this->set('action', $result);
	}

	/**
	* Page
	*
	* List of page names to select one in the page tree
	*/
	protected function setPage ($values) {
		$results = array();

		// Sanitize each item
		if (!empty($values)) {

			// Parse string input
			if (is_string($values)) {
				$results = $this->parseStringInput($values);

			// Try to use it as an array
			} else {
				$values = to_array($values);
				ksort($values);

				// Use each individual item as string, with max 9 supported values as a fail safe
				// FLAG hardcoded maximum count
				$i = 0;
				foreach ($values as $value) {
					if ($this->acceptable($value) and $i < 9) {
						$results[] = $this->normalizeString($value);
					} else {
						break;
					}
					$i++;
				}

			}

		}

		// Select a valid page
		$tree = $this->selectPage($this->servant()->pages()->map(), $results);

		return $this->set('page', $tree);
	}



	/**
	* Private helpers
	*/

	/**
	* Is the value good enough to accept after sanitizing?
	*/
	private function acceptable ($value) {
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
	*   - takes in JSON or "key:value,anotherKey:value;nextSetOfValues;lastSetA,lastSetB"
	*/
	private function parseStringInput ($input) {
		$string = trim($input);

		// Assume JSON
		$temp = json_decode(suffix(prefix($string, '{'), '}'));
		if (is_array($temp)) {
			$results = $temp;

		// Custom shorthand
		} else {
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

		}

		return $result;
	}



	/**
	* Choose one page from those available, preferring the one detailed in $tree
	*/
	private function selectPage ($pagesOnThisLevel, $tree, $level = 0) {
 
		// No preference or preferred item doesn't exist: auto select
		if (!isset($tree[$level]) or !array_key_exists($tree[$level], $pagesOnThisLevel)) {

			// Cut out the rest of the preferred items
			$tree = array_slice($tree, 0, $level);

			// Auto select first item on this level
			$keys = array_keys($pagesOnThisLevel);
			$tree[] = $keys[0];

		}

		// We need to go deeper
		if (is_array($pagesOnThisLevel[$tree[$level]])) {
			return $this->selectPage($pagesOnThisLevel[$tree[$level]], $tree, $level+1);

		// That was it
		} else {
			return array_slice($tree, 0, $level+1);
		}

	}

}

?>