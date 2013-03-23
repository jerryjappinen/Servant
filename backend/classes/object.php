<?php

class ServantObject {

	// Properties
	protected $propertyMain = null;

	// Include a reference to an established Servant parent
	protected function servant () {
		return $this->get('main');
	}



	// Magic methods

	// Generic constructor
	public function __construct ($main) {

		// Take in Servant object
		if (get_class($main) === 'ServantMain') {
			$this->set('main', $main);

		} else {
			return $this->fail('New objects need a main Servant instance');
		}

		return $this;
	}

	// Generic initializer
	public function init () {

		// Also run the optional class-specific method
		if (method_exists($this, 'initialize')) {
			$arguments = func_get_args();
			call_user_func_array(array($this, 'initialize'), $arguments);
		}

		return $this;
	}

	// Default behavior when calling inaccessible method is getAndSet
	public function __call ($id, $arguments) {
		return $this->getAndSet($id, $arguments);
	}

	// When object is used as string, return a name
	public function __toString () {
		if (method_exists($this, 'id')) {
			$name = $this->id();
			return get_class($this).(empty($name) ? '' : ': '.$name);
		} else {
			return get_class($this);
		}
	}



	// Generic functionality

	// Generic getter with traversing options
	protected function get ($id, $tree = null) {
		$propertyName = $this->propertyName($id);
		$value = $this->$propertyName;
		if (is_array($value) and !empty($tree)) {
			return array_traverse($value, array_flatten($tree));
		}
		return $value;
	}

	// Generic property setter, can be used in setter methods
	protected function set ($id, $value) {
		$propertyName = $this->propertyName($id);
		if ($value === null) {
			return $this->fail('Properties cannot be null');
		} else {
			$this->$propertyName = $value;
		}
		return $this;
	}

	// Report failure, throw an error
	protected function dump () {
		$results = array();

		// Accept input in various ways
		$arguments = func_get_args();
		$arguments = array_flatten($arguments);

		// Return what was asked
		$properties = array();
		if ($arguments and !empty($arguments)) {
			$properties = $arguments;

		// Default to dumping all available properties (does not use custom getters)
		} else {
			$classProperties = get_class_vars(get_class($this));
			unset($classProperties[$this->propertyName('main')]);
			foreach (array_keys($classProperties) as $key => $value) {
				$properties[] = $this->unPropertyName($value);
			}
		}

		// Get values
		foreach ($properties as $property) {
			$value = $this->get($property);

			// Call dump() for children, too
			if (is_object($value) and is_subclass_of($value, 'ServantObject')) {
				$results[$property] = ''.$value;
			} else {
				$results[$property] = $value;
			}

		}

		// Only one thing was asked for
		if ($arguments and !empty($arguments) and count($results) === 1) {
			$results = $results[0];
		}

		return $results;
	}

	// Report failure, throw an error
	protected function fail ($message, $code = 500) {
		throw new Exception($message, $code);
		return $this;
	}



	// Wrapper functionality

	// Return true if key exists within property, false if it doesn't
	protected function assert ($id, $tree = null, $target = null) {
		$value = $this->get($id, $tree);
		if ($value === null) {
			return false;
		} else if (isset($target) and $value !== $target) {
			return false;
		} else {
			return true;
		}
	}

	// Call a property-specific setter
	protected function callSetter ($id, $arguments = array()) {
		$setterName = $this->setterName($id);
		if (method_exists($this, $setterName)) {
			return call_user_func_array(array($this, $setterName), $arguments);
		} else {
			return $this->fail(get_class($this).' property "'.$id.'" is missing a setter');
		}
	}



	// Wrapper methods

	// Getter that calls (auto) setter when needed
	protected function getAndSet ($id, $tree = null) {
		if ($this->get($id) === null) {
			$this->callSetter($id);
		}
		return $this->get($id, $tree);
	}

	// Getter that calls (auto) setter when needed
	protected function assertAndSet ($id, $tree = null, $target = null) {
		if ($this->get($id) === null) {
			$this->callSetter($id);
		}
		return $this->assert($id, $tree);
	}

	// Get if values are not provided, but forward to setting if they are
	protected function getOrSet ($id, $arguments = null) {
		if (empty($arguments)) {
			return $this->get($id);
		} else {
			return $this->callSetter($id, $arguments);
		}
	}

	// Getter that can check if a key exists
	protected function getOrAssert ($id, $tree = null, $target = null) {
		if (empty($tree)) {
			return $this->get($id);
		} else {
			return $this->assert($id, $tree, $target);
		}
	}



	// Private helpers

	// Naming convention helpers
	// NOTE reverse namers are slow
	private function propertyName ($id) {
		return 'property'.ucfirst($id);
	}
	private function unPropertyName ($id) {
		$base = substr($id, strlen('property'));
		$name = strtolower(substr($base, 0, 1)).substr($base, 1);
		return $name;
	}
	private function setterName ($id) {
		return 'set'.ucfirst($id);
	}
	private function unSetterName ($id) {
		$base = substr($id, strlen('set'));
		return strtolower(substr($base, 0, 1)).substr($base, 1);
	}

}

?>