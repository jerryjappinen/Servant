<?php

/**
* Actions service (i.e. map of available actions)
*/
class ServantActions extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyAvailable = null;



	/**
	* Convenience
	*/

	public function available ($id = null) {
		$actions = $this->getAndSet('available');

		// Interrogative
		if (isset($id)) {
			$result = false;
			if (in_array($id, $actions)) {
				$result = true;
			}

		// List
		} else {
			$result = $actions;
		}

		return $result;
	}

	public function path ($format = null) {
		return $this->servant()->paths()->actions($format);
	}



	/**
	* Setters
	*/

	/**
	* All available actions: initially as null, action objects created on call
	*/
	protected function setAvailable () {
		$results = array();

		// Find directories under actions
		$dirs = glob_dir($this->path('server'));
		foreach ($dirs as $path) {

			// Only actions with actual PHP code are considered valid
			$files = rglob_files($path, 'php');
			if (!empty($files)) {
				$results[] = basename($path);
			}

			unset($files);
		}

		// Fail if there are no actions
		if (empty($results)) {
			$this->fail('No actions available');
		}

		return $this->set('available', $results);
	}

}

?>