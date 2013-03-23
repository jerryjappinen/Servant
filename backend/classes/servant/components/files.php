<?php

class ServantFiles extends ServantObject {

	// Load utilities upon initialization
	// FLAG this fails, and I don't know why
	// public function initialize () {
	// 	$this->servant()->utilities()->load('markdown');
	// 	return $this;
	// }


	// Open and get file contents in a renderable format
	public function read ($path, $type = '') {

		// Auto file type detection
		if (empty($type)) {
			$type = detect($path, 'extension');
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



	// Run scripts files cleanly
	// Argument 1: path to a file
	// Argument 2: array of variables and values to be created for the script
	// FLAG $this is still what it is
	// FLAG pass servant and other variables given for scripts as parameters
	public function run () {

		if (is_file(func_get_arg(0))) {

			// Set up variables for the script
			foreach (func_get_arg(1) as $key => $value) {
				if (is_string($key) and preg_match($this->servant()->settings()->patterns('safename'), $key)) {
					${$key} = $value;
				}
			}

			// Run each script
			ob_start();

			// Include script
			include func_get_arg(0);

			// Catch output reliably
			$output = ob_get_contents();
			if ($output === false) {
				$output = '';
			}

			// Clear buffer
			ob_end_clean();

		}

		// Return any output
		return $output;
	}



	// Private helpers

	// HTML is already HTML
	private function readHtmlFile ($path) {
		return file_get_contents($path);
	}

	// Markdown converts to HTML
	private function readMdFile ($path) {
		$this->servant()->utilities()->load('markdown');
		return Markdown(file_get_contents($path));
	}

	// PHP file is included elaborately
	private function readPhpFile ($path) {
		return $this->run($path, array('servant' => $this->servant()));
	}

	// Text is assumed to be Markdown
	private function readTxtFile ($path) {
		return $this->readMdFile($path);
	}

}

?>