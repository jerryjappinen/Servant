<?php

class ServantFiles extends ServantObject {

	// Load utilities upon initialization
	public function initialize () {
		$this->load('markdown');
		return $this;
	}


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

	// Load utilities
	public function load () {

		// Accept input in various ways
		$arguments = func_get_args();
		$arguments = array_flatten($arguments);

		// Load utilities
		foreach ($arguments as $name) {
			$path = $this->servant()->paths()->utilities('server').$name;

			// Single file
			if (is_file($path.'.php')) {
				$this->run($path.'.php', true);

			// Directory
			} else if (is_dir($path.'/')) {
				foreach (rglob_files($path.'/', 'php') as $file) {
					$this->run($file, true);
				}

			// Not found
			} else {
				$this->fail('Missing utility '.$name);
			}

		}

		return $this;
	}

	// Run scripts files cleanly
	// Argument 1: path to a file
	// Argument 2: use include_once
	public function run () {

		// Helper shorthand for main object
		$servant = $this->servant();

		// Run each script
		ob_start();

		// Include script
		if (is_file(func_get_arg(0))) {
			if (func_num_args() > 1 and func_get_arg(1)) {
				include_once func_get_arg(0);
			} else {
				include func_get_arg(0);
			}
		}

		// Catch output reliably
		$output = ob_get_contents();
		if ($output === false) {
			$output = '';
		}

		// Clear buffer
		ob_end_clean();

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
		return Markdown(file_get_contents($path));
	}

	// PHP file is included elaborately
	private function readPhpFile () {

		// Helper shorthand for main object
		$servant = $this->servant();

		// Include file, catching output buffer
		ob_start();
		include func_get_arg(0);

		// Catch output reliably
		$output = ob_get_contents();
		if ($output === false) {
			$output = '';
		}

		// Clear buffer
		ob_end_clean();

		// Return any output
		return $output;
	}

	// Text is assumed to be Markdown
	private function readTxtFile ($path) {
		return $this->readMdFile($path);
	}

}

?>