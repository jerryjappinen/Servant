<?php

class ServantTemplate extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyContent 	= null;
	protected $propertyFiles 	= null;
	protected $propertyOutput 	= null;
	protected $propertyPath 	= null;



	/**
	* Public getters
	*/

	public function content ($content = null) {
		$arguments = func_get_args();
		return $this->getOrSet('content', $arguments);
	}

	/**
	* Files can be fetched with their paths in any format
	*/
	public function files ($format = false) {
		$files = $this->getAndSet('files');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}

	/**
	* Paths can be fetched in any format
	*/
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}



	/**
	* Setters
	*/

	/**
	* Template content, whereever it comes
	*/
	protected function setContent ($input = null) {
		$content = '';

		// Normalize multiple parameters
		$arguments = func_get_args();
		if (count($arguments) > 1) {
			$input = $arguments;
		}

		// String input
		if (is_string($input)) {
			$content = trim($input);

		// Array input
		} else if (is_numeric($input)) {
			$content = ''.$input;

		// Array input
		} else if (is_array($input)) {
			$separator = "\n\n";
			$content = implode($separator, array_flatten($input));

		}

		return $this->set('content', $content);
	}

	/**
	* All files of the template
	*/
	protected function setFiles () {
		$files = array();
		$path = $this->path('server');

		// All template files in directory
		if (is_dir($path)) {
			foreach (rglob_files($path, $this->servant()->settings()->formats('templates')) as $file) {

				// Store each file's path to plain format
				$files[] = $this->servant()->format()->path($file, false, 'server');

			}
		}

		return $this->set('files', $files);
	}



	/**
	* Full output
	*/
	protected function setOutput () {
		$result = '';
		foreach ($this->files('server') as $path) {
			$result .= $this->servant()->files()->read($path);
		}
		return $this->set('output', trim($result));
	}



	/**
	* Template is either a file or a folder within the templates directory
	*/
	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->template('plain'));
	}

}

?>