<?php

/**
* A manifest JSON reader
* 
*/
class ServantManifest extends ServantObject {



	/**
	* Properties
	*/
	protected $propertyManifest 	= null;
	protected $propertyPath 		= null;

	// Manifest items
	protected $propertyBrowserCache = null;
	protected $propertyDescriptions = null;
	protected $propertyIcons 		= null;
	protected $propertyLanguages 	= null;
	protected $propertyPageNames 	= null;
	protected $propertyScripts 		= null;
	protected $propertyServerCache 	= null;
	protected $propertySiteNames 	= null;
	protected $propertySitemap 		= null;
	protected $propertySplashImages = null;
	protected $propertyStylesheets 	= null;
	protected $propertyTemplates 	= null;


	/**
	* Take original settings in during initialization (all are optional)
	*/
	public function initialize ($path = null) {
		return $this->setPath($path);
	}



	/**
	* Getters
	*/

	// Original settings
	protected function manifest () {
		$arguments = func_get_args();
		return $this->getAndSet('manifest', $arguments);
	}

	// Path in any format
	protected function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}



	// Manifest items
	public function browserCache () {
		$arguments = func_get_args();
		return $this->getAndSet('browserCache', $arguments);
	}
	public function descriptions () {
		$arguments = func_get_args();
		return $this->getAndSet('descriptions', $arguments);
	}
	public function icons () {
		$arguments = func_get_args();
		return $this->getAndSet('icons', $arguments);
	}
	public function languages () {
		$arguments = func_get_args();
		return $this->getAndSet('languages', $arguments);
	}
	public function pageNames () {
		$arguments = func_get_args();
		return $this->getAndSet('pageNames', $arguments);
	}
	public function scripts () {
		$arguments = func_get_args();
		return $this->getAndSet('scripts', $arguments);
	}
	public function serverCache () {
		$arguments = func_get_args();
		return $this->getAndSet('serverCache', $arguments);
	}
	public function siteNames () {
		$arguments = func_get_args();
		return $this->getAndSet('siteNames', $arguments);
	}
	public function sitemap () {
		$arguments = func_get_args();
		return $this->getAndSet('sitemap', $arguments);
	}
	public function splashImages () {
		$arguments = func_get_args();
		return $this->getAndSet('splashImages', $arguments);
	}
	public function stylesheets () {
		$arguments = func_get_args();
		return $this->getAndSet('stylesheets', $arguments);
	}
	public function templates () {
		$arguments = func_get_args();
		return $this->getAndSet('templates', $arguments);
	}



	/**
	* Setters
	*/

	/**
	* Manifest items (only these are public)
	*/
	public function setBrowserCache () {
		return $this->setAnyHash('browserCache', 'numeric');
	}
	public function setDescriptions () {
		return $this->setAnyHash('descriptions', 'oneliner');
	}
	public function setIcons () {
		return $this->setAnyHash('icons', 'path');
	}
	public function setLanguages () {
		return $this->setAnyHash('languages', 'oneliner');
	}
	public function setPageNames () {
		return $this->setAnyHash('pageNames', 'oneliner');
	}
	public function setScripts () {
		return $this->setAnyHash('scripts', 'path');
	}
	public function setServerCache () {
		return $this->setAnyHash('serverCache', 'numeric');
	}
	public function setSiteNames () {
		return $this->setAnyHash('siteNames', 'oneliner');
	}
	public function setSitemap () {
		$results = array();

		// Find original values
		$manifest = $this->manifest('sitemap');
		if (isset($manifest)) {
			$results = $this->normalizeNodePaths($manifest);
		}

		return $this->set('sitemap', $results);
	}
	public function setSplashImages () {
		return $this->setAnyHash('splashImages', 'path');
	}
	public function setStylesheets () {
		return $this->setAnyHash('stylesheets', 'oneliner');
	}
	public function setTemplates () {
		return $this->setAnyHash('templates', 'trimmed');
	}

	/**
	* Original, unfiltered settings
	*/
	protected function setManifest () {
		$result = array();
		$path = $this->path('server');

		// Look for a settings file
		if (is_file($path)) {

			// Normalize JSON
			$json = trim(file_get_contents($path));
			if (substr($json, 0, 1) !== '{') {
				$json = suffix(prefix($json, '{'), '}');
			}
			$json = json_decode($json, true);

			// Decode JSON file, turn into an array
			if (is_array($json)) {
				$result = $json;
			} else {
				$this->warn('Site settings file ("'.$this->servant()->paths()->format($path, false, 'server').'") is malformed.');
			}

			unset($json);

		}

		return $this->set('manifest', $result);
	}

	/**
	* Path
	*/
	protected function setPath ($path = null) {
		$result = '';

		// Input given
		if (is_string($path)) {
			$path = $this->servant()->paths()->format($path);

			// File missing
			if (!is_file($this->servant()->paths()->format($path, 'server'))) {
				$this->warn('Site settings file '.(!empty($path) ? ' ('.$path.')' : '').'missing.');
			} else {
				$result = $path;
			}

		}

		return $this->set('path', $result);
	}



	/**
	* Private helpers
	*/

	// Setting any manifest item that can be targeted on a node-by-node basis
	private function setAnyHash ($key, $type) {
		$results = array();

		// Find original values
		$manifest = $this->manifest($key);
		if (isset($manifest)) {
			$results = $this->normalizeNodeHash($manifest, $type);
		}

		// Use first value as default/master if it's missing
		// if (count($results) and !isset($results[''])) {
		// 	$keys = array_keys($results);
		// 	$first = reset($keys);
		// 	$results[''] = $results[$first];
		// }

		return $this->set($key, $results);
	}

	// Subarrays with keys are bubbled up. All our manifest arrays are flat, with keys representing nodes
	private function normalizeNodeHash ($input, $type, $prefix = '') {
		$results = array();

		// Normalize input
		if (!is_array($input)) {
			$input = array(
				'' => $input
			);
		}

		// Track prefix on each level
		if (!empty($prefix)) {
			$prefix = suffix($prefix.'/');
		}

		// Each item
		foreach ($input as $keys => $value) {

			// Key string might have multiple IDs
			$keys = $this->normalizeNodeKeys($keys);
			foreach ($keys as $key) {

				$realKey = $prefix.unsuffix(unprefix($key, '/'), '/');

				// Children
				if (is_array($value)) {
					$results = array_merge($results, $this->normalizeNodeHash($value, $type, $prefix.$realKey));

				// Acceptable value
				} else {
					if (!isset($results[$realKey])) {
						$results[$realKey] = array();
					}
					$results[$realKey][] = $this->normalizeNodeHashValue($value, $type);
				}

			}

		}

		return $results;
	}

	// Treat a key that might be a a comma-separated list
	private function normalizeNodeKeys ($input, $prefix = '') {
		$results = array();

		// Paths used as prefixes need a trailing slash
		if (!empty($prefix)) {
			$prefix = suffix($prefix.'/');
		}

		// Indexed array
		if (is_int($input)) {
			$input = '';
		}

		// Split a whitespaced-trimmed string by comma
		foreach (preg_split('/(,|;)/', trim_whitespace($input)) as $value) {

			// Normalize each
			$value = $prefix.unsuffix(unprefix($value, '/'), '/');

			// Add a unique item to results
			if (!in_array($value, $results)) {
				$results[] = $value;
			}

		}

		return $results;
	}

	// Each raw value can be treated in multiple ways
	private function normalizeNodeHashValue ($value, $type) {
		$result = '';

		// Normalize, only accept numeric and string values
		if (is_string($value) or is_numeric($value)) {
			$value = ''.$value;

			// Normalize by selected type
			if ($type === 'numeric') {
				$value = calculate($value);

			// No whitespace
			} else if ($type === 'trimmed') {
				$value = trim_whitespace($value);

			// Paths
			} else if ($type === 'path') {
				$value = trim_text($value, true);

			// One-line string
			} else {
				$value = trim_text($value, true);
			}

			// Accept string
			$result = $value;
		}

		return $result;
	}

	// Page nodes as values (for sitemap)
	private function normalizeNodePaths ($input, $prefix = '') {
		$results = array();

		// Track prefix on each level
		if (!empty($prefix)) {
			$prefix = suffix($prefix.'/');
		}

		if (is_array($input) and !empty($input)) {
			foreach ($input as $keys => $value) {

				// Key string might have multiple IDs
				$keys = $this->normalizeNodeKeys($keys);
				foreach ($keys as $key) {

					// String values go to results directly
					if (is_string($value)) {
						$results[] = $prefix.unsuffix(unprefix(trim_whitespace($value), '/'), '/');

					// Children
					} else if (is_array($value)) {
						$results[] = $prefix.$key;
						$results = array_merge($results, $this->normalizeNodePaths($value, $prefix.$key));
					}

				}
			}
		}

		return $results;
	}

}

?>