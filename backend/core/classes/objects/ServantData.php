<?php

/**
* A data service
*/
class ServantData extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyPath = null;



	/**
	* Convenience
	*/

	public function rglob () {
		$arguments = func_get_args();
		array_unshift($arguments, $this->path('server'));
		return call_user_func_array('rglob', $arguments);
	}



	/**
	* Initialization
	*/
	public function initialize () {
		$arguments = func_get_args();
		return $this->setPath($arguments);
	}



	/**
	* Getters
	*/

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
	* Path
	*/
	protected function setPath () {
		$result = $this->servant()->paths()->data();

		// Get further path
		$arguments = func_get_args();
		$arguments = array_flatten($arguments);
		foreach ($arguments as $value) {

			if (is_string($value)) {

				// Add string to path
				$value = trim($value);
				if (!empty($value)) {
					$result .= suffix($value, '/');
				}

			} else if (is_numeric($value)) {
				$result = $value.'/';
			}

		}

		return $this->set('path', $result);
	}

}

?>