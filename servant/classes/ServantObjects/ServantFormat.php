<?php

class ServantFormat extends ServantObject {

	/**
	* Create human-readable title from something like a filename
	*/
	public function name ($string) {
		$conversions = $this->servant()->settings()->namingConvention();
		return ucfirst(trim(str_ireplace(array_keys($conversions), array_values($conversions), $string)));
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