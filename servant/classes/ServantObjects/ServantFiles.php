<?php

/**
* Files component
*
* Reading template/script files in various formats and converting them into HTML.
*
* Dependencies
*   - utilities()->load()
*   - settings()->formats()
*
* FLAG
*   - Should really have a different name.
*   - Should this be merged with ServantFormat? Or be split into ServantParse.
*/
class ServantFiles extends ServantObject {



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
	*
	* FLAG
	*   - saving PHP files cannot possibly be a good idea...
	*   - uniqid() does not quarantee a unique string (I should create the file in a loop, which cannot possibly be a good idea)
	*/
	private function readHamlFile ($path) {

		// Prepare HAML
		$this->servant()->utilities()->load('mthaml');
		$haml = new MtHaml\Environment('php');

		// Save and read compiled HAML as PHP
		$tempPath = $this->servant()->paths()->temp('server').uniqid(rand(), true).'.php';
		if ($this->saveProcessedFile($tempPath, $haml->compileString(file_get_contents($path), ''))) {
			$output = $this->readPhpFile($tempPath);

		// Didn't work out
		} else {
			$output = '';
		}

		// Clean up
		remove_file($tempPath);

		return $output;
	}

	/**
	* HTML
	*/
	private function readHtmlFile ($path) {
		return file_get_contents($path);
	}

	/**
	* Jade
	*
	* FLAG
	*   - saving PHP files cannot possibly be a good idea...
	*   - uniqid() does not quarantee a unique string (I should create the file in a loop, which cannot possibly be a good idea)
	*/
	private function readJadeFile ($path) {

		// Prepare Jade
		$this->servant()->utilities()->load('jade');
		$jade = new Jade\Jade(true);

		// Save and read compiled Jade as PHP
		$tempPath = $this->servant()->paths()->temp('server').uniqid(rand(), true).'.php';
		if ($this->saveProcessedFile($tempPath, $jade->render(file_get_contents($path)))) {
			$output = $this->readPhpFile($tempPath);

		// Didn't work out
		} else {
			$output = '';
		}

		// Clean up
		remove_file($tempPath);

		// Parse a template
		return $output;
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
	* Twig
	*/
	private function readTwigFile ($path) {

		// Prepare Twig
		$this->servant()->utilities()->load('twig');
		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader);

		// Render Twig
		return $twig->render(file_get_contents($path), array('servant' => $this->servant()));
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



	/**
	* Templates that compile into PHP are run through a temp file
	*/
	private function saveProcessedFile ($path, $content) {

		// Create directory if it doesn't exist
		$directory = dirname($path);
		if (!is_dir($directory)) {
			mkdir($directory, 0777, true);
		}

		// File might already exist
		if (is_file($path)) {
			return false;
		} else {
			return file_put_contents($path, $content);
		}

	}

}

?>