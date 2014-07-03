<?php

/**
* A manifest JSON reader
* 
*/
class ServantManifest extends ServantObject {



	/**
	* Properties
	*/
	protected $propertyJsonMapping   = null;
	protected $propertyRaw           = null;
	protected $propertyPath          = null;

	// Manifest items
	protected $propertyBrowserCaches = null;
	protected $propertyDescriptions  = null;
	protected $propertyIcons         = null;
	protected $propertyLanguages     = null;
	protected $propertyPageNames     = null;
	protected $propertyRedirects     = null;
	protected $propertyScripts       = null;
	protected $propertyServerCaches  = null;
	protected $propertySiteNames     = null;
	protected $propertySitemap       = null;
	protected $propertySplashImages  = null;
	protected $propertyStylesheets   = null;
	protected $propertyTemplates     = null;



	/**
	* Take original settings path in during initialization (all are optional)
	*/
	protected function initialize ($path = null) {
		return $this->setPath($path);
	}



	/**
	* Convenience
	*/



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



	/**
	* Manifest item getters
	*/

	// All
	public function browserCaches ($key = null) {
		return $this->getSomeHash('browserCaches', $key, true);
	}
	public function descriptions ($key = null) {
		return $this->getSomeHash('descriptions', $key, true);
	}
	public function icons ($key = null) {
		return $this->getSomeHash('icons', $key, true);
	}
	public function languages ($key = null) {
		return $this->getSomeHash('languages', $key, true);
	}
	public function pageNames ($key = null) {
		return $this->getSomeHash('pageNames', $key, true);
	}
	public function redirects ($key = null) {
		return $this->getSomeHash('redirects', $key, true);
	}
	public function serverCaches ($key = null) {
		return $this->getSomeHash('serverCaches', $key, true);
	}
	public function siteNames ($key = null) {
		return $this->getSomeHash('siteNames', $key, true);
	}
	public function splashImages ($key = null) {
		return $this->getSomeHash('splashImages', $key, true);
	}
	public function templates ($key = null) {
		return $this->getSomeHash('templates', $key, true);
	}

	// Manifest node hash items with array values
	public function scripts ($key = null) {
		return $this->getSomeHash('scripts', $key, false);
	}
	public function stylesheets ($key = null) {
		return $this->getSomeHash('stylesheets', $key, false);
	}

	// Others
	public function sitemap () {
		$arguments = func_get_args();
		return $this->getAndSet('sitemap', $arguments);
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

			'redirects' 	=> 'redirects',
			'redirect' 		=> 'redirects',

			'scripts' 		=> 'scripts',
			'script' 		=> 'scripts',

			'servercaches' 	=> 'serverCaches',
			'servercache' 	=> 'serverCaches',

			'sitemaps' 		=> 'sitemap',
			'sitemap' 		=> 'sitemap',

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
		return $this->set('browserCaches', $this->treatSomeHash('browserCaches', 'numericOrBoolean'));
	}
	public function setDescriptions () {
		return $this->set('descriptions', $this->treatSomeHash('descriptions', 'oneliner'));
	}
	public function setIcons () {
		return $this->set('icons', $this->treatSomeHash('icons', 'path'));
	}
	public function setLanguages () {
		return $this->set('languages', $this->treatSomeHash('languages', 'oneliner'));
	}
	public function setPageNames () {
		return $this->set('pageNames', $this->treatSomeHash('pageNames', 'oneliner'));
	}
	public function setRedirects () {
		return $this->set('redirects', $this->treatSomeHash('redirects', 'path'));
	}
	public function setServerCaches () {
		return $this->set('serverCaches', $this->treatSomeHash('serverCaches', 'numericOrBoolean'));
	}
	public function setSiteNames () {
		return $this->set('siteNames', $this->treatSomeHash('siteNames', 'oneliner'));
	}
	public function setSplashImages () {
		return $this->set('splashImages', $this->treatSomeHash('splashImages', 'path'));
	}
	public function setTemplates () {
		return $this->set('templates', $this->treatSomeHash('templates', 'trimmed'));
	}



	// Others

	public function setScripts () {
		$manifest = $this->treatSomeHash('scripts', 'path');

		// Internal paths are treated as links to actions
		foreach ($manifest as $key => $paths) {
			foreach ($paths as $i => $path) {
				if (!$this->servant()->paths()->isExternal($path)) {
					$manifest[$key][$i] = $this->servant()->paths()->endpoints().suffix(unprefix($path, '/'), '/');
				}
			}
		}

		return $this->set('scripts', $manifest);
	}

	public function setSitemap () {
		$results = array();

		// Find original values
		$raw = $this->raw('sitemap');
		if (isset($raw)) {
			$results = $this->normalizePointerKeys($raw);
		}

		return $this->set('sitemap', $results);
	}

	public function setStylesheets () {
		$manifest = $this->treatSomeHash('stylesheets', 'path');

		// Internal paths are treated as links to actions
		foreach ($manifest as $key => $paths) {
			foreach ($paths as $i => $path) {
				if (!$this->servant()->paths()->isExternal($path)) {
					$manifest[$key][$i] = $this->servant()->paths()->endpoints().suffix(unprefix($path, '/'), '/');
				}
			}
		}

		return $this->set('stylesheets', $manifest);
	}



	/**
	* Private helpers
	*/

	private function getSomeHash ($hashKey, $nodeKey = null, $firstValueOnly = false, $fallbackToFirstValue = false) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Get actual values
		$raw = $this->getAndSet($hashKey);
		$result = $raw;

		// Sanitize key
		if (is_string($nodeKey)) {
			$nodeKey = $this->sanitizeNodeKey($arguments[0]);
		}

		// Always get default reliably
		$defaultKey = '';
		if ($nodeKey === $defaultKey) {

			// Values available
			if (count($raw) and !isset($raw[$defaultKey]) and $fallbackToFirstValue) {
				$nodeKeys = array_keys($raw);
				$nodeKey = reset($nodeKeys);
				unset($nodeKeys);
			}

		}

		// Find individual item
		if (isset($nodeKey)) {
			$result = isset($raw[$nodeKey]) ? $raw[$nodeKey] : null;

			// Single value only
			if ($firstValueOnly) {
				$result = count($result) ? reset($result) : null;
			}

		// All values
		} else {

			// Single value only for each
			if ($firstValueOnly) {
				foreach ($result as $key => $value) {
					$result[$key] = reset($value);
				}
			}

		}

		return $result;
	}

	// Normalize any manifest item that can be targeted on a node-by-node basis
	private function treatSomeHash ($key, $type) {
		$results = array();

		// Find original values
		$raw = $this->raw($key);
		if (isset($raw)) {
			$results = $this->normalizeNodeHash($raw, $type);
		}

		return $results;
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
		if (is_string($value) or is_numeric($value) or is_bool($value)) {

			// Normalize by selected type
			if ($type === 'numeric') {
				$value = calculate($value);

			// Numeric or boolean
			} else if ($type === 'numericOrBoolean') {
				if (!is_bool($value)) {
					$value = calculate($value);
				}

			// No whitespace
			} else if ($type === 'trimmed') {
				$value = trim_whitespace(''.$value);

			// Paths
			} else if ($type === 'path') {
				$value = $this->resolveFilePath(''.$value);

			// One-line string
			} else {
				$value = trim_text(''.$value, true);
			}

			// Accept string
			$result = $value;
		}

		return $result;
	}

	// Page nodes as values (for sitemap)
	private function normalizePointerKeys ($input, $prefix = '') {
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
						$results = array_merge($results, $this->normalizePointerKeys($value, $prefix.$key));
					}

				}
			}
		}

		return $results;
	}

	private function resolveFilePath ($input) {
		$path = '';

		// A string will do
		if (isset($input) and is_string($input) and !empty($input)) {

			// URL is absolute
			$url = parse_url($input);
			if (isset($url['scheme']) and !empty($url['scheme'])) {
				$path = trim_text($input, true);

			// Sanitize input of internal path
			} else {
				$path = unprefix(unsuffix(trim_text($input, true), '/'), '/');
			}

		}

		return $path;
	}

	private function sanitizeNodeKey ($string) {
		return is_string($string) ? unsuffix(unprefix(trim_whitespace($string), '/'), '/') : $string;
	}

}

?>