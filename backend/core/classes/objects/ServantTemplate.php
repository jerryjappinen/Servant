<?php

/**
* A template
*
* Template objects work without any actual template files. Their content is set to '' by default.
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
	protected $propertyContent 	= null;
	protected $propertyFiles 	= null;
	protected $propertyId 		= null;
	protected $propertyOutput 	= null;
	protected $propertyPage 	= null;
	protected $propertyPath 	= null;



	/**
	* Legacy
	*
	* FLAG
	*   - Replace with multiple template content/parameters (if a template needs this info, it's passed this)
	*/

	public function isSite () {
		return $this->action()->isSite();
	}
	protected $propertyAction 	= null;
	public function action () {
		return $this->get('action');
	}
	protected function setAction ($action) {
		if ($this->getServantClass($action) !== 'action') {
			$this->fail('Invalid action passed to template.');

		// Action is acceptable
		} else {
			return $this->set('action', $action);
		}

	}



	/**
	* Convenience
	*/

	/**
	* Create and initialize a child template
	*
	* FLAG
	*   - I should remove nest methods
	*/
	public function nest ($templateId, $content = null) {

		// Normalize arguments
		$arguments = func_get_args();
		array_shift($arguments);
		array_unshift($arguments, 'template', $templateId, $this->action(), $this->page());

		// Create the template object
		$template = call_user_func_array(array($this, 'generate'), $arguments);

		// Return the output of the template
		return $template->output();
	}



	/**
	* Initialization
	*
	* FLAG
	*   - Way too effortless: action and page should be optional or something
	*/
	public function initialize ($id, $action, $page, $content = null) {
		$arguments = func_get_args();
		$contentArguments = array_slice($arguments, 2);

		// Set ID and action
		$this->setId($id);
		$this->setAction($action);
		$this->setPage($page);

		// Default to empty content
		if (empty($contentArguments)) {
			$contentArguments = array('');
		}

		// Set content
		call_user_func_array(array($this, 'setContent'), $contentArguments);

		return $this;
	}



	/**
	* Getters
	*/

	public function content ($content = null) {
		$arguments = func_get_args();
		return $this->getAndSet('content', $arguments);
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

	public function page () {
		return $this->get('page');
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
	* Template content, whereever it comes
	*/
	protected function setContent ($input = null) {
		$content = '';

		// Normalize multiple parameters
		$arguments = func_get_args();
		$arguments = array_flatten($arguments);

		// String or numerical input
		if (!empty($arguments)) {

			// Normalize all input
			$valids = array();
			foreach ($arguments as $value) {
				$value = $this->normalizeContent($value);
				if (!empty($value)) {
					$valids[] = $value;
				}
			}

			// Accept new content
			if (!empty($valids)) {
				$content = implode("\n\n", $valids);
			}

		}

		return $this->set('content', $content);
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

		// Use template files (might or might not include $template->content())
		if (!empty($files)) {

			// Variables passed to template scripts
			$scriptVariables = array(
				'servant' => $this->servant(),
				'page' => $this->page(),
				'template' => $this,
			);

			$result = $this->servant()->files()->read($files, $scriptVariables);

		// No files - use content directly
		} else {
			$result = $this->content();
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

	/**
	* Current page
	*/
	protected function setPage ($page) {
	
		if ($this->getServantClass($page) !== 'page') {
			$this->fail('Invalid page passed to template.');

		// Page is acceptable
		} else {
			return $this->set('page', $page);
		}

	}



	/**
	* Private helpers
	*/

	private function normalizeContent ($input) {
		$content = '';
		if (is_string($input) or is_numeric($input)) {
			$content = trim(''.$input);
		}
		return $content;
	}

}

?>