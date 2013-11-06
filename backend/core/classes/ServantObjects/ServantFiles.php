<?php

/**
* File reader service
*
* Read content of files of various templating formats
*
* DEPENDENCIES
*   ServantSettings -> formats
*   ServantParse
*   ServantPaths 	-> temp
*
* FLAG
*   - Add support for LESS and SCSS
*/
class ServantFiles extends ServantObject {



	/**
	* Open and get file contents in a renderable format
	*
	* FLAG
	*   - Add support for reading multiple files with shared scriptVariables
	*/
	public function read ($files, $scriptVariables = array()) {
		$result = '';

		// Normalize multiple parameters
		$scriptVariables = func_get_args();
		array_shift($scriptVariables);
		$scriptVariables = array_flatten($scriptVariables, false, true);

		// Single file
		if (is_string($files) and is_file($files)) {
			$result = $this->readFile($files, $scriptVariables);

		// Multiple files
		} else if (is_array($files)) {

		}

		return $result;
	}



	/**
	* Private helpers
	*/

	private function readFile ($path, $scriptVariables) {
		$result = '';

		// Detect file type
		$type = '';
		$extension = pathinfo($path, PATHINFO_EXTENSION);

		// FLAG I should take all formats into account, not just templates
		$formats = $this->servant()->settings()->formats('templates');
		foreach ($formats as $key => $extensions) {
			if (in_array($extension, $extensions)) {
				$type = $key;
				break;
			}
		}

		// Type-specific methods
		$methodName = 'read'.ucfirst($type).'File';
		if ($type and method_exists($this, $methodName)) {
			$result = call_user_func(array($this, $methodName), $path, $scriptVariables);

		// Generic fallback
		} else {
			$result = file_get_contents($path);
		}

		return $result;
	}



	/**
	* Format-specific readers
	*/

	/**
	* HAML
	*
	* FLAG
	*   - saving PHP files cannot possibly be a good idea...
	*   - uniqid() does not quarantee a unique string (I should create the file in a loop, which cannot possibly be a good idea)
	*/
	private function readHamlFile ($path, $scriptVariables = array()) {

		// Save and read compiled HAML as PHP
		$tempPath = $this->servant()->paths()->temp('server').uniqid(rand(), true).'.php';
		if ($this->saveProcessedFile($tempPath, $this->servant()->parse()->hamlToPhp(file_get_contents($path)))) {
			$output = $this->readPhpFile($tempPath, $scriptVariables);

		// Didn't work out
		} else {
			$output = '';
		}

		// Clean up temp file
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
	private function readJadeFile ($path, $scriptVariables = array()) {

		// Save and read compiled Jade as PHP
		$tempPath = $this->servant()->paths()->temp('server').uniqid(rand(), true).'.php';
		if ($this->saveProcessedFile($tempPath, $this->servant()->parse()->jadeToPhp(file_get_contents($path)))) {
			$output = $this->readPhpFile($tempPath, $scriptVariables);

		// Didn't work out
		} else {
			$output = '';
		}

		// Clean up temp file
		remove_file($tempPath);

		return $output;
	}

	/**
	* Markdown
	*/
	private function readMarkdownFile ($path) {
		return $this->servant()->parse()->markdownToHtml(file_get_contents($path));
	}

	/**
	* PHP
	*/
	private function readPhpFile ($path, $scriptVariables = array()) {
		return run_script($path, $scriptVariables);
	}

	/**
	* RST
	*/
	private function readRstFile ($path) {
		return $this->servant()->parse()->rstToHtml(file_get_contents($path));
	}

	/**
	* Textile
	*/
	private function readTextileFile ($path) {
		return $this->servant()->parse()->textileToHtml(file_get_contents($path));;
	}

	/**
	* Twig
	*/
	private function readTwigFile ($path, $scriptVariables = array()) {
		return $this->servant()->parse()->twigToHtml(file_get_contents($path), $scriptVariables);
	}

	/**
	* Wiki markup
	*/
	private function readWikiFile ($path) {
		return $this->servant()->parse()->wikiToHtml(file_get_contents($path));
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