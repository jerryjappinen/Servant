<?php

/**
* Public assets, available as a service
*/
class ServantAssets extends ServantObject {



	/**
	* Properties
	*/

	protected $propertyPath 				= null;
	protected $propertyScripts 				= null;
	protected $propertyStylesheets 			= null;



	/**
	* Take original settings path in during initialization (all are optional)
	*/
	public function initialize ($path = null) {
		return $this->setPath($path);
	}



	/**
	* Getters
	*/

	// Path in any format
	protected function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

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

	// Path
	protected function setPath ($path = null) {
		$result = '';

		// Input given
		if ($path and is_string($path)) {

			// Dir missing
			if (!is_dir($this->servant()->paths()->format($path, 'server'))) {
				$this->warn('Site assets directory '.(!empty($path) ? ' ("'.$path.'")' : '').'missing.');
			} else {
				$result = $this->servant()->paths()->format($path);
			}

		}

		return $this->set('path', $result);
	}

	// Script assets
	protected function setScripts () {
		return
			$this->set(
				'scripts',
				$this->findAssetFiles(
					$this->path('server'),
					$this->servant()->constants()->formats('scripts')
				)
			);
	}

	// Stylesheet assets
	protected function setStylesheets () {
		return
			$this->set(
				'stylesheets',
				$this->findAssetFiles(
					$this->path('server'),
					$this->servant()->constants()->formats('stylesheets')
				)
			);
	}



	/**
	* Private helpers
	*/

	// Find site-wide asset files
	private function findAssetFiles ($path, $formats) {
		$files = array();

		// All files of this type in site's assets directory
		foreach (rglob_files($path, $formats) as $file) {
			$files[] = $this->servant()->paths()->format($file, false, 'server');
		}

		return $files;
	}

}

?>