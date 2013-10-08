<?php

/**
* FLAG remove this class
*/
class ServantAvailable extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyActions 		= null;



	/**
	* Public getters
	*/

	/**
	* Others have extra assertion methods
	*/
	public function action ($id) {
		return in_array($id, $this->actions());
	}



	/**
	* Setters
	*/

	protected function setActions () {
		return $this->set('actions', array_merge($this->findFiles('actions', 'php'), $this->findDirectories('actions')));
	}



	/**
	* Private helpers
	*/

	private function findFiles ($dir, $types) {
		$items = array();
		$files = glob_files($this->servant()->paths()->$dir('server'), $types);
		foreach ($files as $path) {
			$items[] = pathinfo($path, PATHINFO_FILENAME);
		}
		return $items;
	}

	/**
	* Find directories with at least one supported file
	*
	* FLAG
	*   - This is pretty slow, since it filters out empty directories
	*/
	private function findDirectories ($dir, $formats = array()) {
		$items = array();
		$dirs = glob_dir($this->servant()->paths()->$dir('server'));
		foreach ($dirs as $path) {
			$files = rglob_files($path, $formats);
			if (!empty($files)) {
				$items[] = basename($path);
			}
			unset($files);
		}
		return $items;
	}

}

?>