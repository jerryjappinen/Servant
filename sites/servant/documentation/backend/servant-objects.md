
# Servant objects

All classes in Servant (`ServantMain` and all components) extend `ServantObject`. This base class which includes a set of generic functionality and provides a convention to writing classes in Servant. You are free to use them in custom actions if you wish.



### Properties

ServantObjects don't have public properties. All properties are protexted and accessed via protected or public getter methods.

The base class has one property, the ServantMain object:

##### backend/classes/object.php
	// Properties
	protected $propertyMain = null;

	// Include a reference to an established Servant parent
	protected function servant () {
		return $this->get('main');
	}

As you can see, the actual property name is prefixed, but the getter is not. This is a Servant convention. The custom getter will return the property's value via `get()`, which looks like this:

##### backend/classes/object.php
	// Generic getter with traversing options
	protected function get ($id, $tree = null) {
		$propertyName = $this->propertyName($id);
		$value = $this->$propertyName;
		if (is_array($value) and !empty($tree)) {
			return array_traverse($value, array_flatten($tree));
		}
		return $value;
	}
