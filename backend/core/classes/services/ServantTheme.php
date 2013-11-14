<?php

/**
* A theme, available as service
*
* DEPENDENCIES
*   ServantFormat 	-> path
*   ServantPaths	-> theme
*   ServantSettings	-> formats
*/
class ServantTheme extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyScripts 		= null;
	protected $propertyStylesheets 	= null;



	/**
	* Public getters for paths
	*/

	public function path ($format = false) {
		return $this->servant()->paths()->assets($format);
	}

	public function scripts ($format = false) {
		$files = $this->getAndSet('scripts');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}

	public function stylesheets ($format = false) {
		$files = $this->getAndSet('stylesheets');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}



	/**
	* Setters
	*/

	/**
	* Stylesheet files
	*/
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->findFiles($this->servant()->settings()->formats('stylesheets')));
	}

	/**
	* Script files
	*/
	protected function setScripts () {
		return $this->set('scripts', $this->findFiles($this->servant()->settings()->formats('scripts')));
	}



	/**
	* Private helpers
	*/

	private function findFiles ($formats) {
		$files = array();

		// All files of this type in theme's directory
		foreach (rglob_files($this->path('server'), $formats) as $file) {
			$files[] = $this->servant()->format()->path($file, false, 'server');
		}

		return $files;
	}



}

?>