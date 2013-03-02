<?php

class ServantTemplate extends ServantObject {

	// Properties
	protected $propertyFiles 	= null;
	protected $propertyId 		= null;
	protected $propertyPath 	= null;



	// Select ID when initializing
	protected function initialize ($id = false) {
		if ($id) {
			$this->setId($id);
		}
		return $this;
	}



	// Public getters
	public function files ($format = false) {
		$files = $this->getAndSet('files');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}
	public function id () {
		return $this->getAndSet('id');
	}
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}



	// Setters

	protected function setFiles () {
		$files = array();
		foreach (rglob_files($this->path('server'), $this->servant()->settings()->templateLanguages()) as $key => $path) {
			$files[$key] = $this->servant()->format()->path($path, false, 'server');
		}
		return $this->set('files', $files);
	}

	protected function setId ($id = '') {

		// Silently fall back to default
		if (!$this->servant()->available()->template($id)) {
			$id = $this->servant()->available()->templates(0);
		}

		return $this->set('id', $id);
	}

	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->templates('plain').$this->id().'/');
	}

}

?>