<?php

/**
* Formatting service
*
* DEPENDENCIES
*   servant -> paths -> documentRoot
*   servant -> paths -> root
*/
class ServantFormatter extends ServantObject {



	/**
	* Convert a path from one format to another
	*/
	public function path ($path, $resultFormat = null, $originalFormat = null) {

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



}

?>