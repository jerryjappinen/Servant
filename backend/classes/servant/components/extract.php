<?php

class ServantExtract extends ServantObject {

	// Open and get file contents in a renderable format
	public function file ($path, $type = '') {

		// Auto file type detection
		if (empty($type)) {
			$type = detect($path, 'extension');
		}

		// Type-specific methods
		if (method_exists($this, $type.'File')) {
			return call_user_func(array($this, $type.'File'), $path);

		// Generic fallback
		} else {
			return '<pre>'.file_get_contents($path).'</pre>';
		}
	}



	// Private helpers

	// Markdown converts to HTML
	private function mdFile ($path) {
		return Markdown(file_get_contents($path));
	}

	// PHP file is included elaborately
	private function phpFile () {

		// Helper shorthand
		$servant = $this->servant();

		// Include file, catching output buffer
		ob_start();
		include func_get_arg(0);
		$output = ob_get_contents();
		ob_end_clean();

		// Return output buffer
		return $output;
	}

	// Text is assumed to be Markdown
	private function txtFile ($path) {
		return $this->mdFile($path);
	}

}

?>