<?php

/**
* A web site, available as service
*/
class ServantSite_ extends ServantObject {

	/**
	* Properties
	*/

	// Inidividual values, user-defined (in manifest)
	protected $propertyBrowserCache 		= null;
	protected $propertyServerCache 			= null;
	protected $propertyTemplate 			= null;

	// Arrays, user-defined (in manifest)
	protected $propertyExternalScripts 		= null;
	protected $propertyExternalStylesheets 	= null;
	protected $propertyPageNames 			= null;
	protected $propertyPageTemplates 		= null;

	// Not user-defined
	protected $propertyScripts 				= null;
	protected $propertyStylesheets 			= null;

	// protected $propertyDescription 			= null;
	// protected $propertyExternalScripts 		= null;
	// protected $propertyExternalStylesheets 	= null;
	// protected $propertyIcon 				= null;
	// protected $propertyLanguage 			= null;
	// protected $propertyName 				= null;
	// protected $propertyPageDescriptions 	= null;
	// protected $propertyPageNames 			= null;
	// protected $propertyPageOrder 			= null;
	// protected $propertyPageTemplates 		= null;
	// protected $propertySplashImage 			= null;



	/**
	* Getters
	*/

	public function browserCache () {
		return $this->getAndSet('browserCache');
	}

	public function serverCache () {
		return $this->getAndSet('serverCache');
	}

	public function template () {
		return $this->getAndSet('template');
	}

	public function externalScripts () {
		$arguments = func_get_args();
		return $this->getAndSet('externalScripts', $arguments);
	}

	public function externalStylesheets () {
		$arguments = func_get_args();
		return $this->getAndSet('externalStylesheets', $arguments);
	}

	public function pageNames () {
		$arguments = func_get_args();
		return $this->getAndSet('pageNames', $arguments);
	}

	public function pageTemplates () {
		$arguments = func_get_args();
		return $this->getAndSet('pageTemplates', $arguments);
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

	protected function setBrowserCache () {
		return
			$this->set('browserCache',
				$this->resolveCacheTime(
					$this->servant()->manifest()->defaultBrowserCache(),
					$this->servant()->constants()->defaults('browserCache')
				)
			);
	}

	protected function setServerCache () {
		return
			$this->set('serverCache',
				$this->resolveCacheTime(
					$this->servant()->manifest()->defaultServerCache(),
					$this->servant()->constants()->defaults('serverCache')
				)
			);
	}

	/**
	* Page-specific scripts
	*/
	protected function setExternalScripts () {
		$input = $this->servant()->manifest()->defaultScripts();
		return $this->set('externalScripts', (!empty($input) ? $input : array()));
	}

	/**
	* Page-specific stylesheets
	*/
	protected function setExternalStylesheets () {
		$input = $this->servant()->manifest()->defaultStylesheets();
		return $this->set('externalStylesheets', (!empty($input) ? $input : array()));
	}

	/**
	* Page-specific names
	*/
	protected function setPageNames () {
		$input = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->pageNames());
		return $this->set('pageNames', (!empty($input) ? $input : array()));
	}

	/**
	* Page-specific templates
	*/
	protected function setPageTemplates () {
		$input = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->templates());
		return $this->set('pageTemplates', (!empty($input) ? $input : array()));
	}

	/**
	* Script assets
	*/
	protected function setScripts () {
		return $this->set('scripts', $this->findAssetFiles($this->servant()->constants()->formats('scripts')));
	}

	/**
	* Stylesheet assets
	*/
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->findAssetFiles($this->servant()->constants()->formats('stylesheets')));
	}

	/**
	* ID of selected template
	*
	* NOTE
	*   - Site setting is prefererred even when it's not actually available or have any files
	*   - In the absence of site setting, either global default or the first available template are used
	*
	* FLAG
	*   - Template should be page-specific
	*/
	protected function setTemplate () {
		$input = $this->servant()->manifest()->defaultTemplate();
		$template = '';

		// Site settings
		if (!empty($input)) {
			$template = $input;
		}

		// Unavailable
		if (!$this->servant()->available()->template($template)) {
			$path = $this->servant()->paths()->templates('server');

			// Warn of missing template
			if ($this->servant()->debug() and !empty($input)) {
				$this->warn('Attempted using the '.$template.' template, which is not available.');
			}

			// Try default
			$default = $this->servant()->constants()->defaults('template');
			if ($this->servant()->available()->template($default)) {
				$template = $default;

			// If default is unavailable, attempt to use whatever we have
			} else {
				$templates = glob_dir($path);
				if (!empty($templates)) {
					$template = basename($templates[0]);
				}
			}

		}

		return $this->set('template', $template);
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

	// Resolve valid cache time from user input, in minutes
	private function resolveCacheTime ($input, $default) {
		$result = calculate($default);

		// No explicit value given
		if ($input !== null and $input !== true) {

			// Cache disabled
			if (!$input) {
				$input = 0;

			// Formula as a string
			} elseif (is_string($input)) {
				$input = calculate($input, true);
			}

			// Numerical value available
			if (is_numeric($input)) {
				$result = $input;
			}

		}


		return max(0, intval($result));
	}

	private function resolveImageFile ($input) {
		$result = '';

		// A string will do
		if ($input and is_string($input)) {

			// Sanitize input
			$path = unprefix(unsuffix(trim_text($input, true), '/'), '/');

			// File format must be acceptable
			$extension = pathinfo($path, PATHINFO_EXTENSION);
			if (in_array($extension, $this->servant()->constants()->formats('iconImages'))) {

				// File must exist
				if (is_file($this->servant()->paths()->format($path, 'server'))) {
					$result = $path;
				}

			}

		}

		return $result;
	}

}

?>