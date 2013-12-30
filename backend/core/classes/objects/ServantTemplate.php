<?php

/**
* A template
*
* Template objects, potentially with template files
*
* DEPENDENCIES
*   ServantConstants	-> defaults
*   ServantFiles		-> read
*   ServantFormat		-> path
*   ServantPaths		-> template, format
*/
class ServantTemplate extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyContents = null;
	protected $propertyFiles 	= null;
	protected $propertyId 		= null;
	protected $propertyOutput 	= null;
	protected $propertyPath 	= null;



	/**
	* Make a child template (returns output directly)
	*/
	public function nest ($templateId) {

		// Create a template object
		$arguments = func_get_args();
		$template = call_user_func_array(array($this->servant()->create(), 'template'), $arguments);

		// Return the output of the template
		return $template->output();
	}









	/**
	* Convenience
	*/

	public function content () {
		$arguments = func_get_args();
		if (empty($arguments)) {
			$arguments[] = 0;
		}
		return call_user_func_array(array($this, 'contents'), $arguments);
	}



	/**
	* Initialization
	*/

	public function initialize ($id) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Set ID and action
		$this->setId($id);

		// Set content
		if (!empty($arguments)) {
			call_user_func_array(array($this, 'setContents'), $arguments);
		}

		return $this;
	}



	/**
	* Getters
	*/

	public function contents () {
		$arguments = func_get_args();
		return $this->getAndSet('contents', $arguments);
	}

	// Files can be fetched with their paths in any format
	public function files ($format = false) {
		$files = $this->getAndSet('files');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->paths()->format($filepath, $format);
			}
		}
		return $files;
	}

	public function id () {
		return $this->getAndSet('id');
	}

	public function output () {
		return $this->getAndSet('output');
	}

	// Paths can be fetched in any format
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}



	/**
	* Setters
	*/

	/**
	* Zero, one or more pieces of template content - any type, loosely determined by template scripts
	*/
	protected function setContents () {
		$result = array();

		// Take in content items
		$arguments = func_get_args();
		foreach ($arguments as $value) {
			if ($value !== null) {
				$result[] = $value;
			}
		}

		return $this->set('contents', $result);
	}

	/**
	* All files of the template
	*/
	protected function setFiles () {
		$files = array();

		$plainPath = $this->path();
		$serverPath = $this->path('server');

		// All template files in directory
		if (!empty($plainPath) and is_dir($serverPath)) {
			foreach (rglob_files($serverPath, $this->servant()->constants()->formats('templates')) as $file) {

				// Store each file's path to plain format
				$files[] = $this->servant()->paths()->format($file, false, 'server');

			}
		}

		return $this->set('files', $files);
	}



	/**
	* ID (directory name)
	*/
	protected function setId ($id) {

		// Validate ID
		if (!$this->servant()->available()->template($id)) {
			$this->fail($id.' template is not available.');

		// Fail if ID is inappropriate
		} else {
			return $this->set('id', ''.$id);
		}

	}



	/**
	* Full output
	*/
	protected function setOutput () {
		$result = '';
		$files = $this->files('server');

		// Use template files
		if (!empty($files)) {

			// Variables passed to template scripts
			$scriptVariables = array(
				'servant' => $this->servant(),
				'template' => $this,
			);

			$result = $this->servant()->files()->read($files, $scriptVariables);

		// No files - attempt to use content directly
		} else {
			$result = ''.implode("\n\n", $this->contents());
		}

		return $this->set('output', trim($result));
	}



	/**
	* Template is a folder within the templates directory
	*/
	protected function setPath () {
		$path = '';
		$id = $this->id();

		// Acceptable path must be a child folder in the templates dir
		if (!empty($id)) {
			$path = $this->servant()->paths()->template($this->id(), 'plain');
		}

		return $this->set('path', $path);
	}

}

?>