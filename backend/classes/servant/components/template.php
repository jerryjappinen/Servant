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
	// FLAG add formatting support
	public function files () {
		return $this->getAndSet('files');
	}
	public function id () {
		return $this->getAndSet('id');
	}
	// FLAG path is not being set here
	public function path ($format = false) {
		return $this->servant()->paths()->templates($format).$this->id();
	}



	// Setters

	protected function setFiles () {
		return $this->set('files', rglob_files($this->path('server'), $this->servant()->settings()->templateLanguages()));
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