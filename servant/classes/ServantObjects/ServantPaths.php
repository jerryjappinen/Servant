<?php

class ServantPaths extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyDocumentRoot = null;
	protected $propertyRoot 		= null;

	protected $propertyActions 		= null;
	protected $propertyCache 		= null;
	protected $propertyClasses 		= null;
	protected $propertyHelpers 		= null;
	protected $propertyIndex 		= null;
	protected $propertySettings 	= null;
	protected $propertySite 		= null;
	protected $propertyTemp 		= null;
	protected $propertyTemplates 	= null;
	protected $propertyThemes 		= null;
	protected $propertyUtilities 	= null;



	/**
	* Kickstart all paths
	*/
	public function initialize ($paths) {
		$results = array();

		// Check required paths against against what's given
		foreach (array(
			'documentRoot',
			'root',
			'actions',
			'cache',
			'classes',
			'helpers',
			'index',
			'settings',
			'site',
			'temp',
			'templates',
			'themes',
			'utilities',
		) as $key) {

			// Meaningful values are needed
			if (isset($paths[$key]) and !empty($paths[$key])) {
				$this->set($key, $paths[$key]);
			} else {
				$this->fail('Need a proper path for '.$key);
			}
		}

		return $this;
	}



	/**
	* Public getters
	*/

	/**
	* Root paths
	*/

	public function documentRoot () {
		return $this->get('documentRoot');
	}

	public function root ($format = false) {
		if (!$format) {
			return $this->get('root');
		} else {
			return $this->servant()->format()->path('', $format);
		}
	}

	/**
	* Other paths
	*/
	public function actions ($format = false) {
		return $this->servant()->format()->path($this->get('actions'), $format);
	}
	public function cache ($format = false) {
		return $this->servant()->format()->path($this->get('cache'), $format);
	}
	public function classes ($format = false) {
		return $this->servant()->format()->path($this->get('classes'), $format);
	}
	public function helpers ($format = false) {
		return $this->servant()->format()->path($this->get('helpers'), $format);
	}
	public function index ($format = false) {
		return $this->servant()->format()->path($this->get('index'), $format);
	}
	public function settings ($format = false) {
		return $this->servant()->format()->path($this->get('settings'), $format);
	}
	public function site ($format = false) {
		return $this->servant()->format()->path($this->get('site'), $format);
	}
	public function temp ($format = false) {
		return $this->servant()->format()->path($this->get('temp'), $format);
	}
	public function templates ($format = false) {
		return $this->servant()->format()->path($this->get('templates'), $format);
	}
	public function themes ($format = false) {
		return $this->servant()->format()->path($this->get('themes'), $format);
	}
	public function utilities ($format = false) {
		return $this->servant()->format()->path($this->get('utilities'), $format);
	}

}

?>