<?php

class ServantAvailable extends ServantObject {

	// Properties
	protected $propertySites = null;

	// Public getters
	public function site ($needle) {
		return in_array($needle, $this->sites());
	}
	public function sites () {
		return $this->getAndSet('sites', func_get_args());
	}

	// Sites are directories
	protected function setSites () {
		$sites = array();
		$dirs = glob_dir($this->servant()->paths()->sites('server'));
		foreach ($dirs as $value) {
			$sites[] = basename($value);
		}
		return $this->set('sites', $sites);
	}

}

?>