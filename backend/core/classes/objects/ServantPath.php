<?php

/**
* A single path with formatting and related functionality
*
* DEPENDENCIES
*   ServantPaths
*/
class ServantPath extends ServantObject {



	/**
	* Properties
	*/
	protected $propertyRaw 		= null;



	/**
	* Initialization, support setting path straight up
	*/
	public function initialize () {
		$arguments = func_get_args();
		call_user_func_array(array($this, 'add'), $arguments);
		return $this;
	}



	/**
	* Getters
	*/

	public function raw () {
		return $this->getAndSet('raw');
	}

	public function domain () {
		$arguments = func_get_args();
		return $this->format($this->with($arguments), 'domain');
	}

	public function server () {
		$arguments = func_get_args();
		return $this->format($this->with($arguments), 'server');
	}

	public function url () {
		$arguments = func_get_args();
		return $this->format($this->with($arguments), 'url');
	}



	/**
	* Convenience
	*/

	public function add () {
		$arguments = func_get_args();
		return $this->setRaw($this->with($arguments));
	}

	public function with () {
		$path = $this->raw();

		// Normalize parameters
		$components = func_get_args();
		$components = array_flatten($components);

		if (count($components)) {
			if (!empty($path)) {
				$path = suffix($path, '/');
			}

			// Sanitize components (slashes between components)
			$keys = array_keys($components);
			$last = end($keys);
			foreach ($components as $i => $component) {
				$path .= $this->sanitize($component, false, ($i !== $last));
			}

		}

		return $path;
	}



	/**
	* Setters
	*/

	protected function setRaw ($input = null) {
		$result = '';
		if (isset($input) and !empty($input)) {
			$result = $input;
		}
		return $this->set('raw', $result);
	}



	/**
	* Private helpers
	*/

	// private function isAbsolute () {
	// 	$protocol = $this->protocol($this->raw());
	// 	return empty($protocol) ? false : true;
	// }

	// private function protocol () {
	// 	$path = $this->url();
	// 	$result = '';

	// 	// Normalize string
	// 	$url = parse_url($this->trim($url));

	// 	// Parse URL and find protocol
	// 	if (isset($url['scheme']) and !empty($url['scheme'])) {
	// 		$result = $url['scheme'];
	// 	}

	// 	return $result;
	// }

	// private function trim ($string) {
	// 	return is_string($string) ? trim_text($string, true) : '';
	// }

	// public function isRootRelative ($url) {
	// 	$url = $this->trim($url);
	// 	return prefixed(trim($url), '/');
	// }

	// public function isRelative ($url) {
	// 	return is_string($url) and !$this->urlIsAbsolute($url) and !$this->urlIsRootRelative($url);
	// }



	/**
	* Convert a path from one format to another
	*/
	private function format ($path, $resultFormat = null, $originalFormat = null) {

		// Don't do anything if it doesn't make sense
		if ($resultFormat != $originalFormat) {

			// Prefixes
			$documentRoot = $this->servant()->paths()->documentRoot();
			$root = $this->servant()->paths()->root();
			$host = $this->servant()->paths()->host();

			// Strip to plain format
			if ($originalFormat === 'server') {
				$path = unprefix($path, $documentRoot.$root);
			} else if ($originalFormat === 'domain') {
				$path = unprefix($path, $root);
			} else if ($originalFormat === 'url') {
				$path = unprefix(unprefix($path, $host), $root);
			}

			// Add prefixes if needed
			if ($resultFormat === 'server') {
				$path = $documentRoot.$root.$path;
			} else if ($resultFormat === 'domain') {
				$path = prefix($root.$path, '/');
			} else if ($resultFormat === 'url') {
				$path = $host.$root.$path;
			}

		}

		return $path;
	}

	/**
	* Sanitize path formatting
	*
	* Results: '', 'foo/', 'foo/bar/'
	*/
	private function sanitize ($path = '', $leadingSlash = false, $trailingSlash = true) {

		// Meaningful starting value
		if (is_string($path)) {
			$result = trim_text($path, true);
		} else if (is_numeric($path)) {
			$result = ''.$path;
		} else {
			$result = '';
		}

		// Slash prefix
		if ($leadingSlash) {
			$result = prefix($result, '/');
		} else {
			$result = unprefix($result, '/');
		}

		// Valid paths get a trailing slash
		if (!empty($result) and $trailingSlash) {
			$result = suffix($result, '/');
		}

		return $result;
	}

}

?>