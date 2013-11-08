<?php

/**
* Available service
*/
class ServantAvailable extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyActions 		= null;
	protected $propertyTemplates 	= null;



	/**
	* Convenience
	*/

	public function action ($id) {
		return in_array($id, $this->actions());
	}

	public function template ($id) {
		return in_array($id, $this->templates());
	}



	/**
	* Setters
	*/

	/**
	* Actions
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

	/**
	* Templates
	*/
	protected function setTemplates () {
		$results = array();

		// Find directories (even empty ones) under actions
		$dirs = glob_dir($this->servant()->paths()->templates('server'));
		foreach ($dirs as $path) {
			$results[] = basename($path);
		}

		return $this->set('templates', $results);
	}

}

?>