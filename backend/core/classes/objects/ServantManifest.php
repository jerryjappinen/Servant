<?php

/**
* A manifest JSON reader
* 
*/
class ServantManifest extends ServantObject {



	/**
	* Properties
	*/
	protected $propertyJsonMapping 		= null;
	protected $propertyRaw 				= null;
	protected $propertyPath 			= null;

	// Manifest items
	protected $propertyBrowserCaches 	= null;
	protected $propertyDescriptions 	= null;
	protected $propertyIcons 			= null;
	protected $propertyLanguages 		= null;
	protected $propertyPageNames 		= null;
	protected $propertyScripts 			= null;
	protected $propertyServerCaches 	= null;
	protected $propertySiteNames 		= null;
	protected $propertySitemap 			= null;
	protected $propertySplashImages 	= null;
	protected $propertyStylesheets 		= null;
	protected $propertyTemplates 		= null;



	/**
	* Take original settings in during initialization (all are optional)
	*/
	public function initialize ($path = null) {
		return $this->setPath($path);
	}



	/**
	* Getters
	*/

	// JSON key mapping
	protected function jsonMapping () {
		$arguments = func_get_args();
		return $this->getAndSet('jsonMapping', $arguments);
	}

	// Path in any format
	protected function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

	// Original settings
	protected function raw () {
		$arguments = func_get_args();
		return $this->getAndSet('raw', $arguments);
	}



	// Misc manifest items
	public function sitemap () {
		$arguments = func_get_args();
		return $this->getAndSet('sitemap', $arguments);
	}



	// A default for each manifest node hashes items
	public function browserCache () {
		return $this->browserCaches('', true);
	}
	public function description () {
		return $this->descriptions('', true);
	}
	public function icon () {
		return $this->icons('', true);
	}
	public function language () {
		return $this->languages('', true);
	}
	public function pageName () {
		return $this->pageNames('', true);
	}
	public function serverCache () {
		return $this->serverCaches('', true);
	}
	public function siteName () {
		return $this->siteNames('', true);
	}
	public function splashImage () {
		return $this->splashImages('', true);
	}
	public function template () {
		return $this->templates('', true);
	}

	// When one default value doesn't make sense
	public function script () {
		return $this->scripts('');
	}
	public function stylesheet () {
		return $this->stylesheets('');
	}

	// Manifest node hashes items
	public function browserCaches () {
		$arguments = func_get_args();
		array_unshift($arguments, 'browserCaches');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function descriptions () {
		$arguments = func_get_args();
		array_unshift($arguments, 'descriptions');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function icons () {
		$arguments = func_get_args();
		array_unshift($arguments, 'icons');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function languages () {
		$arguments = func_get_args();
		array_unshift($arguments, 'languages');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function pageNames () {
		$arguments = func_get_args();
		array_unshift($arguments, 'pageNames');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function scripts () {
		$arguments = func_get_args();
		array_unshift($arguments, 'scripts');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function serverCaches () {
		$arguments = func_get_args();
		array_unshift($arguments, 'serverCaches');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function siteNames () {
		$arguments = func_get_args();
		array_unshift($arguments, 'siteNames');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function splashImages () {
		$arguments = func_get_args();
		array_unshift($arguments, 'splashImages');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function stylesheets () {
		$arguments = func_get_args();
		array_unshift($arguments, 'stylesheets');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}
	public function templates () {
		$arguments = func_get_args();
		array_unshift($arguments, 'templates');
		return call_user_func_array(array($this, 'getSomeHash'), $arguments);
	}



	/**
	* Setters
	*/

	/**
	* JSON key mapping
	*/
	protected function setJsonMapping () {
		$mapping = array(
			'browsercaches' => 'browserCaches',
			'browsercache' 	=> 'browserCaches',

			'descriptions' 	=> 'descriptions',
			'description' 	=> 'descriptions',

			'icons' 		=> 'icons',
			'icon' 			=> 'icons',

			'languages' 	=> 'languages',
			'language' 		=> 'languages',

			'pagenames' 	=> 'pageNames',
			'pagename' 		=> 'pageNames',

			'scripts' 		=> 'scripts',
			'script' 		=> 'scripts',

			'servercaches' 	=> 'serverCaches',
			'servercache' 	=> 'serverCaches',

			'sitemaps' 		=> 'siteMaps',
			'sitemap' 		=> 'siteMaps',
			'sitenames' 	=> 'siteNames',
			'sitename' 		=> 'siteNames',

			'splashimages' 	=> 'splashImages',
			'splashimage' 	=> 'splashImages',

			'stylesheets' 	=> 'stylesheets',
			'stylesheet' 	=> 'stylesheets',

			'templates' 	=> 'templates',
			'template' 		=> 'templates',

		);
		return $this->set('jsonMapping', $mapping);
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
	* Original, unfiltered settings
	*/
	protected function setRaw () {
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
				$result = array();
				$jsonMapping = $this->jsonMapping();

				foreach ($json as $jsonKey => $value) {

					// Normalize keys
					$jsonKey = mb_strtolower(trim_whitespace($jsonKey));

					// Key valid
					if (isset($jsonMapping[$jsonKey])) {
						$result[$jsonMapping[$jsonKey]] = $value;
					}

				}

				// // Only accept valid items
				// foreach ($this->jsonMapping() as $jsonKey => $realKey) {

				// 	// Already set
				// 	if (!isset($result[$realKey])) {

				// 		// Treat JSON keys as trimmed and case-insensitive
				// 		$jsonKey = mb_strtolower(trim_whitespace($jsonKey));

				// 		// Value set in JSON
				// 		if (isset($json[$jsonKey])) {
				// 			$result[$realKey] = $json[$jsonKey];
				// 		}

				// 	}

				// }

			} else {
				$this->warn('Site settings file ("'.$this->servant()->paths()->format($path, false, 'server').'") is malformed.');
			}

		}

		return $this->set('raw', $result);
	}



	/**
	* Manifest items (only these are public)
	*/
	public function setBrowserCaches () {
		return $this->setSomeHash('browserCaches', 'numeric');
	}
	public function setDescriptions () {
		return $this->setSomeHash('descriptions', 'oneliner');
	}
	public function setIcons () {
		return $this->setSomeHash('icons', 'path');
	}
	public function setLanguages () {
		return $this->setSomeHash('languages', 'oneliner');
	}
	public function setPageNames () {
		return $this->setSomeHash('pageNames', 'oneliner');
	}
	public function setScripts () {
		return $this->setSomeHash('scripts', 'path');
	}
	public function setServerCaches () {
		return $this->setSomeHash('serverCaches', 'numeric');
	}
	public function setSiteNames () {
		return $this->setSomeHash('siteNames', 'oneliner');
	}
	public function setSitemap () {
		$results = array();

		// Find original values
		$raw = $this->raw('sitemap');
		if (isset($raw)) {
			$results = $this->normalizeNodePaths($raw);
		}

		return $this->set('sitemap', $results);
	}
	public function setSplashImages () {
		return $this->setSomeHash('splashImages', 'path');
	}
	public function setStylesheets () {
		return $this->setSomeHash('stylesheets', 'oneliner');
	}
	public function setTemplates () {
		return $this->setSomeHash('templates', 'trimmed');
	}



	/**
	* Private helpers
	*/

	private function getSomeHash ($nodeKey, $key = null, $firstValueOnly = false) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Get actual values
		$raw = $this->getAndSet($nodeKey);
		$result = $raw;

		// Sanitize key
		if (is_string($key)) {
			$key = $this->sanitizeNodeKey($arguments[0]);
		}

		// Always get default reliably
		$defaultKey = '';
		if ($key === $defaultKey) {
			$result = array();

			// Values available
			if (count($raw)) {
				if (isset($raw[$defaultKey])) {
					$result = $raw[$defaultKey];
				} else {
					$result = reset($raw);
				}
			}

			// Single value only
			if ($firstValueOnly) {
				$result = count($result) ? reset($result) : null;
			}

		}

		return $result;
	}

	// Setting any manifest item that can be targeted on a node-by-node basis
	private function setSomeHash ($key, $type) {
		$results = array();

		// Find original values
		$raw = $this->raw($key);
		if (isset($raw)) {
			$results = $this->normalizeNodeHash($raw, $type);
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

				$realKey = $prefix.$this->sanitizeNodeKey($key);

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
			$value = $prefix.$this->sanitizeNodeKey($value);

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
						$results[] = $prefix.$this->sanitizeNodeKey($value);

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

	private function sanitizeNodeKey ($string) {
		return is_string($string) ? unsuffix(unprefix(trim_whitespace($string), '/'), '/') : $string;
	}

}

?>