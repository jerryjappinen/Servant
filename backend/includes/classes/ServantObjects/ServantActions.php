<?php

/**
* Actions service (i.e. map of available actions)
*/
class ServantActions extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyMap 			= null;



	/**
	* Convenience
	*/

	public function available ($id = null) {
		$result = false;
		if ($id and array_key_exists($id, $this->map())) {
			$result = true;
		}
		return $result;
	}



	/**
	* Public getters
	*/

	// Creates action objects dynamicmapy as needed
	public function map () {

		// Business as usual
		$arguments = func_get_args();
		if (empty($arguments)) {
			return $this->getAndSet('map');

		// An action object is called
		} else {
			$map = $this->getAndSet('map');
			$result = null;

			// Validate ID
			$id = $arguments[0];
			if ($this->available($id)) {

				$action = $map[$id];

				// Create action object if needed
				if ($action === null) {	
					$action = create_object(new ServantAction($this->servant()))->init($id);

					// Save action in the map
					$map[$id] = $action;
					$this->set('map', $map);
				}

				$result = $action;				
			}

			return $result;
		}

	}

	public function path ($format = null) {
		return $this->servant()->paths()->actions($format);
	}



	/**
	* Setters
	*/

	/**
	* Tree of selected page
	*/
	protected function setCurrent ($id = null) {

		// Silent fallback
		if (!$this->available($id)) {

			// Global default
			$default = $this->servant()->settings()->defaults('action');
			if ($this->available($default)) {
				$id = $default;

			// Whatever's available
			} else {
				$available = array_keys($this->map());
				$id = $available[0];
			}
		}

		return $this->set('current', $id);
	}

	/**
	* All available actions: initially as null, action objects created on call
	*
	* FLAG
	*   - Files are not lazy loaded, so this is slow
	*/
	protected function setMap () {
		$results = array();

		// Find directories under actions
		$dirs = glob_dir($this->path('server'));
		foreach ($dirs as $path) {

			// Only actions with actual PHP code are considered
			$files = rglob_files($path, array_flatten($this->servant()->settings()->formats('templates')));
			if (!empty($files)) {

				// Directory name is ID
				$results[basename($path)] = null;

			}

			unset($files);
		}

		// Fail if there are no actions
		if (empty($results)) {
			$this->fail('No actions available');
		}

		return $this->set('map', $results);
	}

}

?>