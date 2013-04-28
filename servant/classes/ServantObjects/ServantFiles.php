<?php

/**
* Files component
*
* Reading structure/content files in various formats and converting them into HTML.
*
* Dependencies
*   - utilities()->load()
*   - settings()->formats()
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
		return $this;
	}



	/**
	* Open and get file contents in a renderable format
	*/
	public function read ($path, $type = '') {

		// Automatic file type detection
		if (empty($type)) {
			$extension = pathinfo($path, PATHINFO_EXTENSION);
			foreach ($this->servant()->settings()->formats('templates') as $key => $extensions) {
				if (in_array($extension, $extensions)) {
					$type = $key;
					break;
				}
			}

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
	* HAML
	*/
	private function readHamlFile ($path) {
		$this->servant()->utilities()->load('mthaml');
		$haml = new MtHaml\Environment('php');

		// Run script with eval()
		// FLAG

		ob_start();

		eval(' ?>'.$haml->compileString(file_get_contents($path), '').'<?php ');

		$output = ob_get_contents();
		if ($output === false) {
			$output = '';
		}
		ob_end_clean();

		return $output;
	}

	/**
	* HTML
	*/
	private function readHtmlFile ($path) {
		return file_get_contents($path);
	}

	/**
	* Markdown
	*/
	private function readMarkdownFile ($path) {
		$this->servant()->utilities()->load('markdown');
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
		$this->servant()->utilities()->load('rst');
		return RST(file_get_contents($path));
	}

	/**
	* Textile
	*/
	private function readTextileFile ($path) {
		$this->servant()->utilities()->load('textile');
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
		$this->servant()->utilities()->load('wiky');
		$wiky = new wiky;
		$parsed = $wiky->parse(file_get_contents($path));
		return $parsed ? $parsed : '';
	}

}

?>