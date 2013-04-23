<?php

/**
* Files component
*
* Reading structure/content files in various formats and converting them into HTML.
*
* Dependencies
*   - utilities()->load()
*
* FLAG
*   - Should really have a different name
*   - Should this be merged with ServantFormat?
*/
class ServantFiles extends ServantObject {

	/**
	* Initialization
	*
	* Load utilities needed by the component
	*/
	public function initialize () {
		$this->servant()->utilities()->load('markdown', 'rst', 'textile', 'wiky');
		return $this;
	}



	/**
	* Open and get file contents in a renderable format
	*/
	public function read ($path, $type = '') {


		// Automatic file type detection
		if (empty($type)) {
			$type = pathinfo($path, PATHINFO_EXTENSION);
		}

		// File must exist
		if (is_file($path)) {

			// Type-specific methods
			$methodName = 'read'.ucfirst($type).'File';
			if (method_exists($this, $methodName)) {
				return call_user_func(array($this, $methodName), $path);

			// Generic fallback
			} else {
				return '<pre>'.file_get_contents($path).'</pre>';
			}

		// File doesn't exist
		} else {
			return '';
		}
	}



	/**
	* Private helpers for each format
	*/

	/**
	* HTML
	*/
	private function readHtmlFile ($path) {
		return file_get_contents($path);
	}

	/**
	* Markdown
	*/
	private function readMdFile ($path) {
		return Markdown(file_get_contents($path));
	}

	/**
	* PHP
	*/
	private function readPhpFile ($path) {
		return run_script($path, array('servant' => $this->servant()));
	}

	/**
	* RST
	*
	* FLAG
	*   - parser is incomplete
	*/
	private function readRstFile ($path) {
		return RST(file_get_contents($path));
	}

	/**
	* Textile
	*/
	private function readTextileFile ($path) {
		$parser = new Textile();
		return $parser->textileThis(file_get_contents($path));;
	}

	/**
	* Plain text
	*/
	private function readTxtFile ($path) {
		return $this->readMdFile($path);
	}

	/**
	* Wiki markup
	*
	* FLAG
	*   - parser is incomplete
	*/
	private function readWikiFile ($path) {
		$wiky = new wiky;
		$parsed = $wiky->parse(file_get_contents($path));
		return $parsed ? $parsed : '';
	}

}

?>