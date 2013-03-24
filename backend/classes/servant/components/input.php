<?php

class ServantInput extends ServantObject {

	// Properties
	protected $propertyAction 	= null;
	protected $propertyArticle 	= null;
	protected $propertySite 	= null;



	// Take input
	public function initialize ($inputs) {

		// Set defaults
		$this->setAction('');
		$this->setArticle(array());
		$this->setSite('');

		// Select things if we have any
		if (!empty($inputs)) {
			foreach ($inputs as $key => $value) {
				if (in_array($key, array('action', 'article', 'site'))) {
					$this->callSetter($key, array($value));
				}
			}
		}

		return $this;
	}



	// Setters

	protected function setAction ($input) {
		if ($this->accept($input)) {
			$this->set('action', $this->normalize($input));
		}
		return $this;
	}

	// List of article names to select one in the article tree
	protected function setArticle ($input) {
		$input = to_array($input);
		ksort($input);

		$results = array();

		// Sanitize each item
		if (!empty($input)) {

			// Parse string input
			if (is_string($input)) {

				// Assume JSON
				$temp = json_decode($input);
				if (is_array($temp)) {
					$input = $temp;

				// Shorthand
				} else {
					$input = shorthand_decode($input);
				}

			}

			// Use array items
			if (is_array($input)) {
				foreach ($input as $value) {
					if ($this->accept($value)) {
						$results[] = $this->normalize($value);
					}
				}
			}

		}

		return $this->set('action', $results);
	}

	protected function setSite ($input) {
		if ($this->accept($input)) {
			$this->set('site', $this->normalize($input));
		}
		return $this;
	}



	// Private helpers

	// Is the value good enough to accept after sanitizing?
	private function accept ($value) {
		return !empty($value) and (is_string($value) or is_int($value));
	}

	// Normalize slightly bad input
	private function normalize () {
		return trim(strval($input));
	}

}

?>