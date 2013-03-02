<?php

class ServantFormat extends ServantObject {

	// Create human-readable title from something like a filename
	public function name ($string) {
		return ucfirst(str_ireplace(array_keys($this->servant()->settings()->namingConvention()), array_values($this->servant()->settings()->namingConvention()), $string));
	}

	// Convert a plain path into any format
	public function path ($path, $resultFormat = false, $originalFormat = false) {

		// Don't do anything if it doesn't make sense
		if ($resultFormat != $originalFormat) {
			// Prefixes
			$root = $this->servant()->paths()->root();
			$documentRoot = $this->servant()->paths()->documentRoot();

			// Strip to plain format
			if ($originalFormat === 'server') {
				$path = dont_start_with($path, $documentRoot.$root);
			} else if ($originalFormat === 'domain') {
				$path = dont_start_with($path, $root);
			}

			// Add prefixes if needed
			if ($resultFormat === 'server') {
				$path = $documentRoot.$root.$path;
			} else if ($resultFormat === 'domain') {
				$path = start_with($root.$path, '/');
			}

		}

		return $path;
	}

}

?>