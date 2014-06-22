<?php

/**
* A web site, available as service
*/
class ServantSite extends ServantObject {

	/**
	* Properties
	*/

	// Inidividual values, user-defined (in manifest)
	protected $propertyBrowserCache 		= null;
	protected $propertyDescription 			= null;
	protected $propertyIcon 				= null;
	protected $propertyLanguage 			= null;
	protected $propertyName 				= null;
	protected $propertyServerCache 			= null;
	protected $propertySplashImage 			= null;
	protected $propertyTemplate 			= null;

	// Arrays, user-defined (in manifest)
	protected $propertyExternalScripts 		= null;
	protected $propertyExternalStylesheets 	= null;
	protected $propertyPageDescriptions 	= null;
	protected $propertyPageNames 			= null;
	protected $propertyPageTemplates 		= null;

	// Not user-defined
	protected $propertyScripts 				= null;
	protected $propertyStylesheets 			= null;

	// Sitemap
	protected $propertyPageOrder 			= null;

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

	public function description () {
		return $this->getAndSet('description');
	}

	public function externalScripts () {
		$arguments = func_get_args();
		return $this->getAndSet('externalScripts', $arguments);
	}

	public function externalStylesheets () {
		$arguments = func_get_args();
		return $this->getAndSet('externalStylesheets', $arguments);
	}

	public function icon ($format = null) {
		$path = $this->getAndSet('icon');
		if ($format and !empty($path)) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

	public function language () {
		return $this->getAndSet('language');
	}

	public function name () {
		return $this->getAndSet('name');
	}

	public function pageDescriptions () {
		$arguments = func_get_args();
		return $this->getAndSet('pageDescriptions', $arguments);
	}

	public function pageNames () {
		$arguments = func_get_args();
		return $this->getAndSet('pageNames', $arguments);
	}

	public function pageOrder () {
		$arguments = func_get_args();
		return $this->getAndSet('pageOrder', $arguments);
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

	public function serverCache () {
		return $this->getAndSet('serverCache');
	}

	public function splashImage ($format = null) {
		$path = $this->getAndSet('splashImage');
		if ($format and !empty($path)) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
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

	public function template () {
		return $this->getAndSet('template');
	}



	/**
	* Setters
	*/

	// Default browser cache
	protected function setBrowserCache () {
		return
			$this->set('browserCache',
				$this->resolveCacheTime(
					$this->servant()->manifest()->defaultBrowserCache(),
					$this->servant()->constants()->defaults('browserCache')
				)
			);
	}

	// Default description
	protected function setDescription () {
		$input = $this->servant()->manifest()->defaultDescription();
		return $this->set('description', (!empty($input) ? $input : ''));
	}

	// Page-specific scripts
	protected function setExternalScripts () {
		$input = $this->servant()->manifest()->defaultScripts();
		return $this->set('externalScripts', (!empty($input) ? $input : array()));
	}

	// Page-specific stylesheets
	protected function setExternalStylesheets () {
		$input = $this->servant()->manifest()->defaultStylesheets();
		return $this->set('externalStylesheets', (!empty($input) ? $input : array()));
	}

	// Path to site icon comes from settings or remains an empty string
	protected function setIcon () {
		$input = $this->servant()->manifest()->defaultIcon();
		return $this->set('icon', (!empty($input) ? $input : ''));
	}

	// Default language
	protected function setLanguage () {
		$input = $this->servant()->manifest()->defaultLanguage();
		return $this->set('language', (!empty($input) ? $input : ''));
	}

	// Default site name
	protected function setName () {

		// Name set in manifest
		$input = $this->servant()->manifest()->defaultSiteName();
		if (isset($input)) {
			$result = $input;

		// Fallback
		} else {

			// Generate from folder name
			$folderName = basename(unprefix(unsuffix($this->servant()->paths()->root(), '/'), '/'));
			$conversions = $this->servant()->constants()->namingConvention();
			$folderName = ucfirst(trim(str_ireplace(array_keys($conversions), array_values($conversions), $folderName)));
			if (!empty($folderName)) {
				$result = $folderName;

			// From constants
			} else {
				$result = $this->servant()->constants()->defaults('siteName');
			}
		}

		return $this->set('name', $result);
	}

	// Page-specific descriptions
	protected function setPageDescriptions () {
		$input = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->descriptions());
		return $this->set('pageDescriptions', (!empty($input) ? $input : array()));
	}

	// Page-specific names
	protected function setPageNames () {
		$input = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->pageNames());
		return $this->set('pageNames', (!empty($input) ? $input : array()));
	}

	// Manual page order configuration - page ordering and page properties
	protected function setPageOrder () {
		$input = $this->servant()->manifest()->sitemap();
		return $this->set('pageOrder', !empty($input) ? $input : array());
	}

	// Page-specific templates
	protected function setPageTemplates () {
		$input = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->templates());
		return $this->set('pageTemplates', (!empty($input) ? $input : array()));
	}

	// Script assets
	protected function setScripts () {
		return $this->set('scripts', $this->findAssetFiles($this->servant()->constants()->formats('scripts')));
	}

	// Default server cache
	protected function setServerCache () {
		return
			$this->set('serverCache',
				$this->resolveCacheTime(
					$this->servant()->manifest()->defaultServerCache(),
					$this->servant()->constants()->defaults('serverCache')
				)
			);
	}

	// Path to site splash image comes from settings or remains an empty string
	protected function setSplashImage () {
		$input = $this->servant()->manifest()->defaultSplashImage();
		return $this->set('splashImage', (!empty($input) ? $input : ''));
	}

	// Stylesheet assets
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

}

?>