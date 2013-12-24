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

	public function open () {
		$arguments = func_get_args();

		// Accept either root-relative or relative path
		$path = implode('/', $arguments);
		if (!prefixed($path, '/')) {
			$path = $this->path('server').$path;
		}

		// Get contents
		$result = '';
		if (is_file($path)) {
			$result = file_get_contents($path);
		}

		return $result;
	}

	public function rglob () {
		$arguments = func_get_args();
		array_unshift($arguments, $this->path('server'));
		return call_user_func_array('rglob', $arguments);
	}

	public function store ($path, $content = '') {

		// Accept either root-relative or relative path
		if (!prefixed($path, '/')) {
			$path = $this->path('server').$path;
		}

		// Support multiple contents
		$arguments = func_get_args();
		array_shift($arguments);
		$content = implode('', array_flatten($arguments));

		// Create directory
		$directory = dirname($path);
		if (!is_dir($directory)) {

			// Catch permissions fail
			if (!@mkdir($directory, 0777, true)) {
				$this->fail('Can\'t create data directory ("'.$this->servant()-paths()->format($directory, 'plain', 'server').'").');
			}

		}

		// Write to file
		if (file_put_contents($path, $content) === false) {
			$this->fail('Writing data file failed ('.$this->servant()-paths()->format($path, 'plain', 'server').')');
		}

		return $this;
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