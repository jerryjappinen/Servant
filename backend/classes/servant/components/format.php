<?php

class ServantFormat extends ServantObject {

	// Create human-readable title from something like a filename
	public function name ($string) {
		return str_ireplace(array(
			'-',
			'_',
			' and ',
			'+',
			'url',
			'jquery',
			'js ',
		), array(
			' ',
			' ',
			' &amp; ',
			' &amp; ',
			'URL',
			'jQuery',
			'JS ',
		), ucfirst($string));
	}

	// Convert a plain path into any format
	public function path ($path, $format = false) {

		// Add document root and root
		if ($format === 'server') {
			$path = $this->servant()->paths()->documentRoot().$this->servant()->paths()->root().$path;

		// Usable in client
		} else if ($format === 'domain') {
			$path = start_with($this->servant()->paths()->root().$path, '/');
		}

		return $path;
	}

}

?>