<?php

class ServantObject {

	// Properties
	protected $propertyMain = null;

	// Include a reference to an established Servant parent
	protected function servant () {
		return $this->get('main');
	}

	// Generic constructor
	public function __construct ($main) {

		// Take in Servant object
		if (get_class($main) === 'ServantMain') {
			$this->set('main', $main);

			// Optionally call custom initialization method
			if (method_exists($this, 'initialize')) {
				$temp = func_get_args();
				array_shift($temp);
				call_user_func_array(array($this, 'initialize'), $temp);
			}

		} else {
			$this->fail('Objects must be created with a main instance');
		}

		return $this;
	}



	// Naming convention helpers
	protected function propertyName ($id) {
		return 'property'.ucfirst($id);
	}
	protected function setterName ($id) {
		return 'set'.ucfirst($id);
	}



	// Generic getter with traversing options
	protected function get ($id, $tree = false) {
		$propertyName = $this->propertyName($id);
		$value = $this->$propertyName;
		if (is_array($value) and !empty($tree)) {
			return array_traverse($value, $tree);
		}
		return $value;
	}

	// Generic property setter, can be used in setter methods
	protected function set ($id, $value) {
		$propertyName = $this->propertyName($id);
		if ($value === null) {
			throw new Exception('Cannot set null as property values', 500);
		} else {
			$this->$propertyName = $value;
		}
		return $this;
	}

	// Throw error
	protected function fail ($message, $code = 500) {
		throw new Exception($message, $code);
		return $message;
	}



	// Special getter functionality

	// Get if values are not provided, but forward to setting if they are
	protected function getOrSet ($id, $arguments = null) {

		// Get
		if (empty($arguments)) {
			return $this->get($id);
		} else {

			// Custom setters exists, pass values on
			$setterName = $this->setterName($id);
			if (method_exists($this, $setterName)) {
				return call_user_func_array(array($this, $setterName), $arguments);

			// No custom setter, store values as it was given
			} else {
				return $this->set($id, count($arguments) > 1 ? $arguments : $arguments[0]);
			}

		}
	}

	// Getter with a transparent call to (auto) setter if needed
	protected function getAndSet ($id, $tree = false) {

		// Need to set for the first time
		if ($this->get($id) === null) {

			// Custom setters exists
			$setterName = $this->setterName($id);
			if (method_exists($this, $setterName)) {
				call_user_func(array($this, $setterName))->get($id);

			// Can't set
			} else {
				throw new Exception('The '.get_class($this).' property "'.$id.'" is missing a setter method', 500);
			}

		}

		// Get
		return $this->get($id, $tree);
	}

	// Getter that can check if a key exists
	protected function getOrAssert ($id, $assertableTree = false) {

		// Get
		if (empty($assertableTree)) {
			return $this->get($id);

		// Return true if key exists, false if it doesn't
		} else {
			$search = $this->get($id, $assertableTree);
			return $search === null ? false : true;
		}

	}

}

?>