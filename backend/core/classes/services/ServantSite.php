<?php

/**
* A web site, available as service
*/
class ServantSite extends ServantObject {

	/**
	* Properties
	*/

	// Site-wide assets
	protected $propertyScripts 				= null;
	protected $propertyStylesheets 			= null;



	/**
	* Getters
	*/

	public function scripts ($format = false) {
		$files = $this->getAndSet('scripts');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->paths()->format($filepath, $format);
			}
		}
		return $files;
	}

	public function stylesheets ($format = false) {
		$files = $this->getAndSet('stylesheets');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->paths()->format($filepath, $format);
			}
		}
		return $files;
	}



	/**
	* Setters
	*/

	// Script assets
	protected function setScripts () {
		return $this->set('scripts', $this->findAssetFiles($this->servant()->constants()->formats('scripts')));
	}

	// Stylesheet assets
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->findAssetFiles($this->servant()->constants()->formats('stylesheets')));
	}



	/**
	* Private helpers
	*/

	// Find site-wide asset files
	private function findAssetFiles ($formats) {
		$files = array();

		// All files of this type in site's assets directory
		foreach (rglob_files($this->servant()->paths()->assets('server'), $formats) as $file) {
			$files[] = $this->servant()->paths()->format($file, false, 'server');
		}

		return $files;
	}

}

?>