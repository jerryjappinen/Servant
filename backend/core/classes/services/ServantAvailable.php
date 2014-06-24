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
	protected $propertyUtilities 	= null;



	/**
	* Convenience
	*/

	public function action ($id) {
		return in_array($id, $this->actions());
	}

	public function template ($id) {
		return in_array($id, $this->templates());
	}

	public function utility ($id) {
		return in_array($id, $this->utilities());
	}



	/**
	* Getters
	*/

	public function actions () {
		$arguments = func_get_args();
		return $this->getAndSet('actions', $arguments);
	}

	public function templates () {
		$arguments = func_get_args();
		return $this->getAndSet('templates', $arguments);
	}

	public function utilities () {
		$arguments = func_get_args();
		return $this->getAndSet('utilities', $arguments);
	}



	/**
	* Setters
	*/

	/**
	* Actions
	*/
	protected function setActions () {
		$path = $this->servant()->paths()->actions('server');
		$formats = 'php';
		return $this->set('actions', $this->findNonEmptyDirs($path, $formats));
	}

	/**
	* Templates
	*/
	protected function setTemplates () {
		$path = $this->servant()->paths()->templates('server');
		$formats = $this->servant()->constants()->formats('templates');
		return $this->set('templates', $this->findNonEmptyDirs($path, $formats));
	}

	/**
	* Utilities
	*/
	protected function setUtilities () {
		$path = $this->servant()->paths()->utilities('server');
		$formats = 'php';
		return $this->set('utilities', $this->findNonEmptyDirs($path, $formats));
	}



	/**
	* Private helpers
	*/

	private function findNonEmptyDirs ($path, $formats = array()) {
		$results = array();

		// Find directories (even empty ones) under actions
		$dirs = glob_dir($path);
		foreach ($dirs as $dir) {

			// Filter out directories without appropriate files
			$files = rglob_files($dir, $formats);
			if (!empty($files)) {
				$results[] = basename($dir);
			}

		}

		return $results;
	}

}

?>