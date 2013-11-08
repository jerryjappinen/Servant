<?php

/**
* Available service
*/
class ServantAvailable extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyActions = null;



	/**
	* Convenience
	*/

	public function action ($id) {
		return in_array($id, $this->actions());
	}



	/**
	* Setters
	*/

	/**
	* All available actions
	*/
	protected function setActions () {
		$results = array();

		// Find directories under actions
		$dirs = glob_dir($this->servant()->paths()->actions('server'));
		foreach ($dirs as $path) {

			// Only actions with actual PHP code are considered valid
			$files = rglob_files($path, 'php');
			if (!empty($files)) {
				$results[] = basename($path);
			}

			unset($files);
		}

		return $this->set('actions', $results);
	}

}

?>