<?php

class ServantFiles extends ServantObject {

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
	public function run () {

		// Helper shorthand for main object
		$servant = $this->servant();

		// Run each script
		ob_start();

		// We only support PHP files
		if (is_file(func_get_arg(0)) and detect(func_get_arg(0), 'extension') === 'php') {
			include func_get_arg(0);
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