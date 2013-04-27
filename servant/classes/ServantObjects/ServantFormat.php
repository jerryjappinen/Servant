<?php

/**
* Formatting service
*
* Dependencies
*   - servant()->site()->settings()
*   - servant()->paths()->documentRoot()
*   - servant()->paths()->root()
*/
class ServantFormat extends ServantObject {

	/**
	* Create human-readable title from something like a filename
	*/
	public function name ($string, $replacements = null) {

		// Fallback to site replacement settings
		if (!is_array($replacements)) {
			$replacements = $this->servant()->site()->settings('names');
		}

		// Use predefined replacement as name
		$key = mb_strtolower($string);
		if (array_key_exists($key, $replacements)) {
			$name = $replacements[$key];

		// Format ID into something human-readable automatically
		} else {
			$conversions = $this->servant()->settings()->namingConvention();
			$name = ucfirst(trim(str_ireplace(array_keys($conversions), array_values($conversions), $string)));
		}

		return $name;
	}

	/**
	* Convert a path from one format to another
	*/
	public function path ($path, $resultFormat = null, $originalFormat = null) {

		// Don't do anything if it doesn't make sense
		if ($resultFormat != $originalFormat) {

			// Prefixes
			$root = $this->servant()->paths()->root();
			$documentRoot = $this->servant()->paths()->documentRoot();

			// Strip to plain format
			if ($originalFormat === 'server') {
				$path = unprefix($path, $documentRoot.$root);
			} else if ($originalFormat === 'domain') {
				$path = unprefix($path, $root);
			}

			// Add prefixes if needed
			if ($resultFormat === 'server') {
				$path = $documentRoot.$root.$path;
			} else if ($resultFormat === 'domain') {
				$path = prefix($root.$path, '/');
			}

		}

		return $path;
	}

}

?>