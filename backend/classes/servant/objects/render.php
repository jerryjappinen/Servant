<?php

class ServantRender extends ServantObject {

	// Read file and try to output
	public function file ($path) {
		$temp = detect($path, 'extension');

		// Type-specific rendering
		if (method_exists($this, $temp.'File')) {
			return call_user_func(array($this, $temp.'File'), $path, array(), 'foo');

		// Fallback
		} else {
			return file_get_contents($path);
		}
	}

	// Convert to HTML from Markdown
	public function mdFile ($path) {
		return Markdown(file_get_contents($path));
	}

	// Include a PHP file
	public function phpFile () {

		// Helper shorthand
		$servant = $this->servant();

		// Include file, catching output buffer
		ob_start();
		include_once func_get_arg(0);
		$output = ob_get_contents();
		ob_end_clean();

		// Return output buffer
		return $output;
	}

	// Assume Markdown
	public function txtFile ($path) {
		return $this->mdFile($path);
	}

}

?>