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

			// Optionally call class-specific initialization method
			if (method_exists($this, 'initialize')) {
				$temp = func_get_args();
				array_shift($temp);
				call_user_func_array(array($this, 'initialize'), $temp);
			}

		} else {
			return $this->fail('New objects need a main Servant instance');
		}

		return $this;
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



	// Special methods

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
	protected function getOrAssert ($id, $tree = null) {
		if (empty($tree)) {
			return $this->get($id);
		} else {
			return $this->assert($id, $tree);
		}
	}



	// Private helpers

	// Naming convention helpers
	private function propertyName ($id) {
		return 'property'.ucfirst($id);
	}
	private function setterName ($id) {
		return 'set'.ucfirst($id);
	}

}

?>