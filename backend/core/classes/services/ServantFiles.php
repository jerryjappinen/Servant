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

	/**
	* Read individual file
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
	* Templates that compile into PHP are evaluated through a temp file
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
		if ($this->saveProcessedFile($tempPath, $this->convertHamlToPhp(file_get_contents($path)))) {
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
	private function readHtmlFile ($path, $scriptVariables = array()) {
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
		if ($this->saveProcessedFile($tempPath, $this->convertJadeToPhp(file_get_contents($path)))) {
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
	private function readMarkdownFile ($path, $scriptVariables = array()) {
		return $this->convertMarkdownToHtml(file_get_contents($path));
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
	private function readRstFile ($path, $scriptVariables = array()) {
		return $this->convertRstToHtml(file_get_contents($path));
	}

	/**
	* Textile
	*/
	private function readTextileFile ($path, $scriptVariables = array()) {
		return $this->convertTextileToHtml(file_get_contents($path));;
	}

	/**
	* Twig
	*/
	private function readTwigFile ($path, $scriptVariables = array()) {
		return $this->convertTwigToHtml(file_get_contents($path), $scriptVariables);
	}

	/**
	* Wiki markup
	*/
	private function readWikiFile ($path, $scriptVariables = array()) {
		return $this->convertWikiToHtml(file_get_contents($path));
	}






	/**
	* Format-to-format converters
	*/

	/**
	* PHP from HAML
	*/
	private function convertHamlToPhp ($haml, $scriptVariables = array()) {
		$this->servant()->utilities()->load('mthaml');
		$parser = new MtHaml\Environment('php');
		return $parser->compileString($haml, '');
	}

	/**
	* PHP from Jade
	*/
	private function convertJadeToPhp ($jade, $scriptVariables = array()) {
		$this->servant()->utilities()->load('jade');
		$parser = new Jade\Jade(true);
		return $parser->render($jade);
	}

	/**
	* HTML from Markdown
	*/
	private function convertMarkdownToHtml ($markdown, $scriptVariables = array()) {
		$this->servant()->utilities()->load('markdown');
		return Markdown($markdown);
	}

	/**
	* HTML from RST
	*
	* FLAG
	*   - parser is incomplete
	*/
	private function convertRstToHtml ($rst, $scriptVariables = array()) {
		$this->servant()->utilities()->load('rst');
		return RST($rst);
	}

	/**
	* HTML from Textile
	*/
	private function convertTextileToHtml ($textile, $scriptVariables = array()) {
		$this->servant()->utilities()->load('textile');
		return create_object('Textile')->textileThis($textile);
	}

	/**
	* HTML from Twig
	*/
	private function convertTwigToHtml ($twig, $scriptVariables = array()) {
		$this->servant()->utilities()->load('twig');
		return create_object('Twig_Environment', create_object('Twig_Loader_String'))->render($twig, $scriptVariables);
	}

	/**
	* HTML from Plain text
	*/
	private function convertTxtToHtml ($txt, $scriptVariables = array()) {
		return $this->markdownToHtml($txt);
	}

	/**
	* HTML from Wiki markup
	*
	* FLAG
	*   - parser is incomplete
	*/
	private function convertWikiToHtml ($wiki, $scriptVariables = array()) {
		$this->servant()->utilities()->load('wiky');
		$parser = new wiky;
		$html = $parser->parse($wiki);
		return $html ? $html : '';
	}









	/**
	* Run a script file cleanly (no visible variables left around).
	*
	* @param 1 ($file)
	*   Path to a file.
	*
	* @param 2 ($scriptVariables)
	*   Array of variables and values to be created for the script.
	*
	* @param 3 ($queue)
	*   Array of other scripts to include, with variables carried over from previous scripts. When a missing file is encountered, execution on the queue stops.
	*
	* @return 
	*   String content of output buffer after the script has run, false on failure.
	*/
	private function run_script () {
		$output = false;

		$file = func_get_arg(0);
		if (is_file($file)) {
			unset($file);

			// Set up variables for the script
			foreach (func_get_arg(1) as $____key => $____value) {
				if (is_string($____key) and !in_array($____key, array('____key', '____value'))) {
					${$____key} = $____value;
				}
			}
			unset($____key, $____value);

			// Run each script
			ob_start();

			// Include script
			include func_get_arg(0);

			// Store script variables
			$definedVars = get_defined_vars();

			// Catch output reliably
			$output = ob_get_contents();
			if ($output === false) {
				$output = '';
			}

			// Clear buffer
			ob_end_clean();

			// More scripts to include
			if (func_num_args() > 2) {

				// Normalize queue
				$queue = func_get_arg(2);
				$queue = array_flatten(to_array($queue));
				$next = array_shift($queue);

				// Run other scripts
				$others = run_script($next, $definedVars, $queue);
				if ($others !== false) {
					return $output.$others;
				}

			}

		}

		// Return any output
		return $output;
	}



	/**
	* Shortcut to running a script consisting of multiple files with run_script.
	*
	* @param 1 ($files)
	*   Path to the script files.
	*
	* @param 2 ($scriptVariables)
	*   Array of variables and values to be created for the script (initially).
	*
	* @return 
	*   String content of output buffer after the script has run, false on failure.
	*/
	private function run_scripts ($files = array(), $scriptVariables = array()) {
		$queue = array();
		$first = '';

		// Normalize
		$files = array_flatten(to_array($files));
		if (count($files) >= 1) {
			$first = array_shift($files);
			$queue = $files;
		}

		return run_script($first, $scriptVariables, $queue);
	}

}

?>