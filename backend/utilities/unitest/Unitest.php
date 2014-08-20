<?php

/**
* Unitest 0.1.0
*
* A one-class miniature unit testing framework for PHP.
*
* This class is a test suite that can contain test methods and child suites. It can also search for test files in the file system, generating suites automatically.
*
* Test results are reported as raw array data, which can then be converted into HTML, JSON or any other format easily.
*
*
*
* Released under MIT License
* Authored by Jerry Jäppinen
* http://eiskis.net/
* eiskis@gmail.com
*
* https://bitbucket.org/Eiskis/unitest/
*/

class Unitest {



	/**
	* Properties
	*/
	private $_propertyChildren = array();
	private $_propertyInjections = array();
	private $_propertyParent = null;

	private $_baseClass = 'Unitest';
	private $_testMethodPrefix = 'test';
	private $_assertionMethodPrefix = 'should';



	/**
	* Initialization
	*/
	final public function __construct () {
		$this->_runHook('init');
		return $this;
	}



	/**
	* String conversion
	*/
	final public function __toString () {
		return get_class($this);
	}



	/**
	* All assertion methods of this suite (methods beginning with should)
	*/
	final public function assertions () {
		$assertions = array();
		$ref = new ReflectionClass($this);

		// All methods beginning with the prefix 'should'
		foreach ($ref->getMethods() as $method) {
			if (substr($method->name, 0, strlen($this->_assertionMethodPrefix)) === $this->_assertionMethodPrefix) {
				$assertions[] = $method->name;
			}
		}

		return $assertions;
	}



	/**
	* Run tests, some or all
	*/
	final public function run () {
		$arguments = func_get_args();

		$ref = new ReflectionClass($this);

		$results = array(
			'class'    => $this->_className($this),
			'file'     => $this->_classFile($this),
			'line'     => $this->_classLineNumber($this),
			'parents'  => $this->parents(),

			'duration' => 0,

			'failed'   => 0,
			'passed'   => 0,
			'skipped'  => 0,

			'tests'    => array(),
			'children' => array(),
		);

		// Default to tests of this and children arguments
		if (empty($arguments)) {
			$arguments = array($this->tests(), $this->children());
		}

		// Flatten arguments
		$suitesOrTests = $this->_flattenArray($arguments);

		// Preparation before suite runs anything (possible exceptions are left uncaught)
		$this->_runHook('beforeTests');

		// Run tests
		foreach ($suitesOrTests as $suiteOrTest) {

			// Child suite
			if ($this->_isValidSuite($suiteOrTest)) {
				$childResults = $suiteOrTest->run(array_merge($suiteOrTest->tests(), $suiteOrTest->children()));
				$results['children'][] = $childResults;

				// Iterate counters
				foreach (array('failed', 'passed', 'skipped') as $key) {
					$results[$key] = $results[$key] + $childResults[$key];
					$results['duration'] += $childResults['duration'];
				}

			// Test method
			} else if (is_string($suiteOrTest)) {
				$testResult = $this->test($suiteOrTest);
				$results['tests'][] = $testResult;

				// Iterate counters
				$results[$testResult['status']]++;
				$results['duration'] += $testResult['duration'];

			}

		}

		// Clean-up after suite has run everything (exceptions are left uncaught)
		$this->_runHook('afterTests');

		return $results;
	}



	/**
	* Initialize suites in locations
	*/
	final public function scrape () {
		$arguments = func_get_args();

		// Load classes automatically (arguments passed to loadFiles)
		$classes = $this->_execute('_loadFiles', $arguments);

		// Treat classes
		foreach ($classes as $key => $values) {
			$classes[$key] = $this->_generateClassMap($values);
		}
		$classes = $this->_mergeClassMap($classes);

		if (!empty($classes)) {
			$parents = array_reverse(class_parents($this));
			$parents[] = $this->_className($this);

			// Find own class from class map, only generate child suites from own child classes
			foreach ($parents as $parent) {
				if (isset($classes[$parent])) {
					$classes = $classes[$parent];
				} else {
					break;
				}
			}

			// We generate a map of required test suite classes here
			$suites = $this->_generateSuites($classes);

		}

		return $this;
	}



	/**
	* Run an individual test method
	*/
	final public function test ($method) {
		$injections = array();
		$result = $this->skip();
		$duration = 0;

		if (method_exists($this, $method)) {
			$startTime = microtime(true);

			// Take a snapshot of current injections
			$allInjectionsCopy = $this->injections();

			// Contain exceptions of test method
			try {

				// Preparation method
				$this->_runHook('beforeTest');

				// Get innjections to pass to test method
				foreach ($this->_methodParameterNames($this, $method) as $parameterName) {
					$injections[] = $this->injection($parameterName);
				}

				// Call test method
				$result = $this->_execute($method, $injections);

			// Fail test if there are exceptions
			} catch (Exception $e) {
				$result = $this->fail($this->_stringifyException($e));
			}

			// Contain exceptions of clean-up
			try {
				$this->_runHook('afterTest');
			} catch (Exception $e) {
				$result = $this->fail($this->_stringifyException($e));
			}

			// Restore injections as they were before the test
			$this->_propertyInjections = $allInjectionsCopy;

			$duration = microtime(true) - $startTime;
		}

		// Test report
		return array(
			'class'      => $this->_className($this),
			'duration'   => $duration,
			'method'     => $method,
			'file'       => $this->_classFile($this),
			'line'       => $this->_methodLineNumber($this, $method),
			'status'     => $this->assess($result),
			'message'    => $result,
			'injections' => $injections,
		);
	}



	/**
	* All test methods of this suite
	*/
	final public function tests () {
		$tests = array();

		// All class methods
		foreach (get_class_methods($this) as $method) {

			// Class methods with the correct prefix
			if (substr($method, 0, strlen($this->_testMethodPrefix)) === $this->_testMethodPrefix) {

				// Prefixed methods that aren't declared in base class
				$ref = new ReflectionMethod($this, $method);
				$class = $ref->getDeclaringClass();
				if ($class->name !== $this->_baseClass) {
					$tests[] = $method;
				}

			}
		}

		return $tests;
	}



	/**
	* Add a suite as a child of this suite
	*/
	final public function child ($child) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if ($this->_isValidSuite($argument)) {

				// Store reference to this in the child
				$argument->parent($this, true);

				// Add to own flock
				$this->_propertyChildren[] = $argument;

			}
		}
		return $this;
	}



	/**
	* Child suites
	*/
	final public function children () {

		// Set
		$arguments = func_get_args();
		if (!empty($arguments)) {
			return $this->_execute('child', $arguments);
		}

		// Get
		return $this->_propertyChildren;
	}



	/**
	* Parent suite
	*/
	final public function parent ($parent = null, $parentKnows = false) {

		// Set
		if (isset($parent)) {

			// Validate parent
			if (!$this->_isValidSuite($parent)) {
				throw new Exception('Invalid parent suite passed as parent.');
			} else {

				// Parent case adds this to its flock if needed
				if (!$parentKnows) {
					$parent->child($this);
				}

				// This stores a reference to its dad
				$this->_propertyParent = $parent;

			}

			return $this;
		}

		// Get
		return $this->_propertyParent;
	}



	/**
	* All parents
	*/
	final public function parents () {
		$parents = array();
		if ($this->parent()) {
			$parents = array_merge($this->parent()->parents(), array($this->_className($this->parent())));
		}
		return $parents;
	}



	/**
	* Remove an injectable value
	*/
	final public function eject ($name) {
		$arguments = func_get_args();
		$arguments = $this->_flattenArray($arguments);
		foreach ($arguments as $argument) {
			if ($this->isInjection($argument)) {
				unset($this->_propertyInjections[$argument]);
			}
		}
		return $this;
	}



	/**
	* Add an injectable value that can be passed to functions as parameter
	*/
	final public function inject ($name, $value) {
		if (is_string($name)) {

			// Sanitize variable name
			$name = str_replace('-', '', preg_replace('/\s+/', '', $name));
			if (!empty($name)) {
				$this->_propertyInjections[$name] = $value;
			}

		}
		return $this;
	}



	/**
	* Get or set an injectable value
	*/
	final public function injection ($name) {

		// Set
		$arguments = func_get_args();
		if (func_num_args() > 1) {
			return $this->_execute('inject', $arguments);
		}

		// Get own injections, bubble
		$injections = $this->injections();
		if (array_key_exists($name, $injections)) {
			return $injections[$name];
		}

		// Missing injection
		throw new Exception('Missing injection "'.$name.'".');
		return $this;
	}



	/**
	* Values available for test methods
	*/
	final public function injections () {

		// Set
		$arguments = func_get_args();
		if (!empty($arguments)) {
			return $this->_execute('inject', $arguments);
		}

		// Get own injections, bubble
		$results = array();
		if ($this->parent()) {
			$results = array_merge($results, $this->parent()->injections());
		}
		$results = array_merge($results, $this->_propertyInjections);	


		return $results;
	}



	/**
	* Find out if injection is available
	*/
	final public function isInjection ($name) {
		$arguments = func_get_args();
		$arguments = $this->_flattenArray($arguments);
		$injections = $this->injections();

		// Fail if one of the equested injections is not available
		foreach ($arguments as $argument) {
			if (!array_key_exists($argument, $injections)) {
				return false;
			}
		}

		return true;
	}



	/**
	* Truey
	*/
	final protected function should ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!$argument) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Equality
	*/
	final protected function shouldBeEqual ($value) {
		$arguments = func_get_args();
		$count = count($arguments);
		if ($count > 1) {
			for ($i = 1; $i < $count; $i++) { 
				if ($arguments[$i-1] !== $arguments[$i]) {
					return $this->fail();
				}
			}
		}
		return $this->pass();
	}



	/**
	* Falsey
	*/
	final protected function shouldNot ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if ($argument) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Non-equality
	*/
	final protected function shouldNotBeEqual ($value) {
		$arguments = func_get_args();
		return !$this->_execute('shouldBeEqual', $arguments);
	}



	/**
	* Class exists
	*/
	final protected function shouldBeAvailableClass ($value) {
		if (!class_exists($value)) {
			$this->fail();
		}
		return $this->pass();
	}



	/**
	* Should be of a specific class.
	*
	* Fails if passed non-objects or no objects.
	*/
	final protected function shouldBeOfClass ($object, $class) {

		// Not an object
		if (!is_object($object)) {
			return $this->fail();

		// Wrong class
		} else if (get_class($object) !== $class) {
			return $this->fail();
		}

		return $this->pass();
	}



	/**
	* Object or class should be of any class that extends a specific class or classes.
	*
	* Can be passed multiple parent target classes.
	*/
	final protected function shouldExtendClass ($objectOrClass, $targetClass) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test for wrong class
		foreach ($arguments as $argument) {
			if (!is_subclass_of($objectOrClass, $argument)) {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A directory should exist in given location(s)
	*/
	final protected function shouldBeDirectory ($path) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_dir($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* A file should exist in given location(s)
	*/
	final protected function shouldBeFile ($path) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_file($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* A file or directory should exist in given location(s)
	*/
	final protected function shouldBeFileOrDirectory ($path) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_file($argument) and !is_dir($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* File(s) should have been included in current PHP script.
	*/
	final protected function shouldBeIncludedFile ($path) {
		$arguments = func_get_args();
		$arguments = $this->_flattenArray($arguments);
		$loadedFiles = get_included_files();

		foreach ($arguments as $argument) {
			if (!is_string($argument) or !in_array(realpath($argument), $loadedFiles)) {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A file or directory should NOT exist in given location(s)
	*/
	final protected function shouldNotBeFileOrDirectory ($path) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (is_file($argument) or is_dir($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* An abstract method should exist in class or object.
	*/
	final protected function shouldHaveAbstractMethod ($objectOrClass, $method) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given methods
		foreach ($arguments as $argument) {
			if (!method_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else {

				// Use reflection to check method
				$ref = new ReflectionMethod($objectOrClass, $argument);
				if (!$ref->isAbstract()) {
					return $this->fail();
				}

			}
		}

		return $this->pass();
	}



	/**
	* An unoverridable method should exist in class or object.
	*/
	final protected function shouldHaveFinalMethod ($objectOrClass, $method) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given methods
		foreach ($arguments as $argument) {
			if (!method_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else {

				// Use reflection to check method
				$ref = new ReflectionMethod($objectOrClass, $argument);
				if (!$ref->isFinal()) {
					return $this->fail();
				}

			}
		}

		return $this->pass();
	}



	/**
	* A method should exist in class or object.
	*/
	final protected function shouldHaveMethod ($objectOrClass, $method) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given methods
		foreach ($arguments as $argument) {
			if (!method_exists($objectOrClass, $argument)) {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A method with the visibility "private" should exist in class or object.
	*/
	final protected function shouldHavePrivateMethod ($objectOrClass, $method) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given methods
		foreach ($arguments as $argument) {
			if (!method_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else if ($this->_methodVisibility($objectOrClass, $argument) !== 'private') {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A method with the visibility "protected" should exist in class or object.
	*/
	final protected function shouldHaveProtectedMethod ($objectOrClass, $method) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given methods
		foreach ($arguments as $argument) {
			if (!method_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else if ($this->_methodVisibility($objectOrClass, $argument) !== 'protected') {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A method with the visibility "public" should exist in class or object.
	*/
	final protected function shouldHavePublicMethod ($objectOrClass, $method) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given methods
		foreach ($arguments as $argument) {
			if (!method_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else if ($this->_methodVisibility($objectOrClass, $argument) !== 'public') {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A static method should exist in class.
	*/
	final protected function shouldHaveStaticMethod ($objectOrClass, $method) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given methods
		foreach ($arguments as $argument) {
			if (!method_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else {

				// Use reflection to check method
				$ref = new ReflectionMethod($objectOrClass, $argument);
				if (!$ref->isStatic()) {
					return $this->fail();
				}

			}
		}

		return $this->pass();
	}



	/**
	* A property with the visibility "private" should exist in class or object.
	*/
	final protected function shouldHavePrivateProperty ($objectOrClass, $property) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given properties
		foreach ($arguments as $argument) {
			if (!property_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else if ($this->_propertyVisibility($objectOrClass, $argument) !== 'private') {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A property should exist in class or object.
	*/
	final protected function shouldHaveProperty ($objectOrClass, $property) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given properties
		foreach ($arguments as $argument) {
			if (!property_exists($objectOrClass, $argument)) {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A property with the visibility "protected" should exist in class or object.
	*/
	final protected function shouldHaveProtectedProperty ($objectOrClass, $property) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given properties
		foreach ($arguments as $argument) {
			if (!property_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else if ($this->_propertyVisibility($objectOrClass, $argument) !== 'protected') {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A property with the visibility "public" should exist in class or object.
	*/
	final protected function shouldHavePublicProperty ($objectOrClass, $property) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given properties
		foreach ($arguments as $argument) {
			if (!property_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else if ($this->_propertyVisibility($objectOrClass, $argument) !== 'public') {
				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* A static property should exist in class.
	*/
	final protected function shouldHaveStaticProperty ($objectOrClass, $property) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given properties
		foreach ($arguments as $argument) {
			if (!property_exists($objectOrClass, $argument)) {
				return $this->fail();
			} else {

				// Use reflection to check property
				$ref = new ReflectionProperty($objectOrClass, $argument);
				if (!$ref->isStatic()) {
					return $this->fail();
				}

				return $this->fail();
			}
		}

		return $this->pass();
	}



	/**
	* Value's type should be array.
	*/
	final protected function shouldBeArray ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_array($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Value's type should be boolean
	*/
	final protected function shouldBeBoolean ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_bool($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Value's type should be float
	*/
	final protected function shouldBeFloat ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_float($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Value's type should be integer
	*/
	final protected function shouldBeInteger ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_int($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Value's type should be null
	*/
	final protected function shouldBeNull ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_null($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Value's type should be object
	*/
	final protected function shouldBeObject ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_object($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Value's type should be string
	*/
	final protected function shouldBeString ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_string($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Array value(s) should not contain subarrays
	*/
	final protected function shouldBeFlatArray ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_array($argument)) {
				return $this->fail();
			} else {

				// Fail if child array key found
				foreach ($argument as $child) {
					if (is_array($child)) {
						return $this->fail();
					}
				}

			}
		}
		return $this->pass();
	}



	/**
	* Array value's keys should be sequential and numerical.
	*/
	final protected function shouldHaveIndexedKeys ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_array($argument)) {
				return $this->fail();
			} else {

				// Fail if incorrect key found
				$keys = array_keys($argument);
				for ($i = 0; $i < count($array); $i++) { 
					if ($keys[$i] !== $i) {
						return $this->fail();
					}
				}

			}
		}
		return $this->pass();
	}



	/**
	* Array value's keys should be numerical, potentially non-sequential.
	*/
	final protected function shouldHaveNumericalKeys ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_array($argument)) {
				return $this->fail();
			} else {

				// Fail if incorrect key found
				foreach (array_keys($argument) as $key) {
					if (!is_int($key)) {
						return $this->fail();
					}
				}

			}
		}
		return $this->pass();
	}



	/**
	* Array value's keys should be strings.
	*/
	final protected function shouldHaveStringKeys ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_array($argument)) {
				return $this->fail();
			} else {

				// Fail if incorrect key found
				foreach (array_keys($argument) as $key) {
					if (!is_string($key)) {
						return $this->fail();
					}
				}

			}
		}
		return $this->pass();
	}



	/**
	* Numeric value should be above zero
	*/
	final protected function shouldBeAboveZero ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_numeric($argument) or $argument <= 0) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Numeric value should be below zero
	*/
	final protected function shouldBeBelowZero ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_numeric($argument) or $argument >= 0) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Numeric value should be exactly zero
	*/
	final protected function shouldBeZero ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_numeric($argument) or $argument < 0 or $argument > 0) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* String value's length should be zero
	*/
	final protected function shouldBeEmptyString ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!is_string($argument) or strlen($argument)) {
				return $this->fail();
			}
		}
		return $this->pass();
	}



	/**
	* Assess a value like it was a test result
	*/
	final protected function assess ($value) {
		if ($this->passes($value)) {
			return 'passed';
		} else if ($this->skips($value)) {
			return 'skipped';
		}
		return 'failed';
	}



	/**
	* Test can fail with false, or a message (any value but null or true)
	*/
	final protected function fail () {
		$arguments = func_get_args();
		$count = func_num_args();

		// Empty value is returned as false, otherwise returned as message
		if ($count === 1) {
			return !empty($arguments[0]) ? $arguments[0] : false;

		// Multiple values provided, fail with those as message
		} else if ($count > 1) {
			return $arguments;
		}

		// Default to false
		return false;
	}



	/**
	* Assess failure
	*/
	final protected function fails ($value) {
		return !($this->passes($value) or $this->skips($value));
	}



	/**
	* Test always passes with true
	*/
	final protected function pass () {
		return true;
	}



	/**
	* Assess pass
	*/
	final protected function passes ($value) {
		return $value === true;
	}



	/**
	* Test skipped with null
	*/
	final protected function skip () {
		return true;
	}



	/**
	* Assess skip
	*/
	final protected function skips ($value) {
		return $value === null;
	}



	/**
	* File where this class or object is defined in
	*/
	final private function _classFile ($classOrObject) {
		$ref = new ReflectionClass($classOrObject);
		return $ref->getFileName();
	}



	/**
	* Line number of the file where this class or object is defined in
	*/
	final private function _classLineNumber ($classOrObject) {
		$ref = new ReflectionClass($classOrObject);
		return $ref->getStartLine();
	}



	/**
	* Name of this class or object
	*/
	final private function _className ($classOrObject) {
		return get_class($classOrObject);
	}



	/**
	* Validate a suite object
	*/
	final private function _isValidSuite ($case) {
		return isset($case) and is_object($case) and (
			get_class($case) === $this->_baseClass or
			is_subclass_of($case, $this->_baseClass)
		);
	}



	/**
	* Validate a suite class
	*/
	final private function _isValidSuiteClass ($class) {
		$ref = new ReflectionClass($class);
		if ($class === $this->_baseClass or $ref->isSubclassOf($this->_baseClass)) {
			return true;
		}
		return false;
	}



	/**
	* Get the line number where method is defined in within its class file
	*/
	final private function _methodLineNumber ($classOrObject, $method) {
		$ref = new ReflectionMethod($classOrObject, $method);
		return $ref->getStartLine();
	}



	/**
	* List the names of the function parameters a method is expecing
	*/
	final private function _methodParameterNames ($classOrObject, $method) {
		if (method_exists($classOrObject, $method)) {
			$results = array();
			$ref = new ReflectionMethod($classOrObject, $method);
			foreach ($ref->getParameters() as $parameter) {
				$results[] = $parameter->name;
			}
			return $results;
		}
		return null;
	}



	/**
	* Get the visibility of a method of any object or class
	*/
	final private function _methodVisibility ($classOrObject, $method) {
		if (method_exists($classOrObject, $method)) {
			$ref = new ReflectionMethod($classOrObject, $method);
			if ($ref->isPrivate()) {
				return 'private';
			} else if ($ref->isProtected()) {
				return 'protected';
			}
			return 'public';
		}
		return null;
	}



	/**
	* Get the visibility of a property of any object or class
	*/
	final private function _propertyVisibility ($classOrObject, $propertyName) {
		if (property_exists($classOrObject, $propertyName)) {
			$ref = new ReflectionProperty($classOrObject, $propertyName);
			if ($ref->isPrivate()) {
				return 'private';
			} else if ($ref->isProtected()) {
				return 'protected';
			}
			return 'public';
		}
		return null;
	}



	/**
	* Run own method with arguments
	*/
	final private function _execute ($method, $arguments) {
		if (method_exists($this, $method)) {

			// Get errors as exceptions
			set_error_handler('__UnitestHandleError');

			// Run method
			$result = call_user_func_array(array($this, $method), (is_array($arguments) ? $arguments : array($arguments)));

			// Restore previous error handler
			restore_error_handler();

			return $result;
		}
		// return null;
	}



	/**
	* Flatten an array
	*/
	final private function _flattenArray ($array) {
		$result = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = array_merge($result, $this->_flattenArray($value));
			} else {
				$result[] = $value;
			}
		}
		return $result;
	}



	/**
	* Find directories
	*/
	final private function _globDir ($path = '') {

		// Normalize path
		if (!empty($path)) {
			$path = preg_replace('/(\*|\?|\[)/', '[$1]', $path);
			if (substr($path, -1) !== '/') {
				$path .= '/';
			}
		}

		// Find directories in the path
		$directories = glob($path.'*', GLOB_MARK | GLOB_ONLYDIR);
		foreach ($directories as $key => $value) {
			$directories[$key] = str_replace('\\', '/', $value);
		}
		
		// Sort results
		usort($directories, 'strcasecmp');

		return $directories;
	}



	/**
	* Find files
	*/
	final private function _globFiles ($path = '', $filetypes = array()) {
		$files = array();

		// Handle filetype input
		if (empty($filetypes)) {
			$brace = '';
		} else {
			$brace = '.{'.implode(',', $filetypes).'}';
		}

		// Handle path input
		if (!empty($path)) {
			$path = preg_replace('/(\*|\?|\[)/', '[$1]', $path);
			if (substr($path, -1) !== '/') {
				$path .= '/';
			}
		}

		// Do the glob()
		foreach (glob($path.'*'.$brace, GLOB_BRACE) as $value) {
			if (is_file($value)) {
				$files[] = $value;
			}
		}

		// Sort results
		usort($files, 'strcasecmp');

		return $files;
	}



	/**
	* Find files recursively
	*/
	final private function _rglobFiles ($path = '', $filetypes = array()) {

		// Accept file type restrictions as a single array or multiple independent values
		$arguments = func_get_args();
		array_shift($arguments);
		$filetypes = $this->_flattenArray($arguments);

		// Run glob_files for this directory and its subdirectories
		$files = $this->_globFiles($path, $filetypes);
		foreach ($this->_globDir($path) as $child) {
			$files = array_merge($files, $this->_rglobFiles($child, $filetypes));
		}

		return $files;
	}



	/**
	* Run a suite's hook method, passing it injections
	*/
	final private function _runHook ($hookMethod) {
		$injections = array();

		if (method_exists($this, $hookMethod)) {

			// Get innjections to pass to hook method
			foreach ($this->_methodParameterNames($this, $hookMethod) as $parameterName) {
				$injections[] = $this->injection($parameterName);
			}

			$this->_execute($hookMethod, $injections);

		}

		return $this;
	}



	/**
	* Represent exception as string
	*/
	final private function _stringifyException ($e) {
		return ''.$e->getMessage().' ('.$e->getFile().' line '.$e->getLine().', error code '.$e->getCode().')';
	}



	/**
	* Find out which classes will be defined in a script
	*/
	final private function _classesInScript ($code = '') {
		$classes = array();

		// Find tokens that are classes
		$tokens = token_get_all($code);
		for ($i = 2; $i < count($tokens); $i++) {

			// Assess tokens to find class declarations of subclasses
			if (
				$tokens[$i-2][0] === T_CLASS and
				$tokens[$i-1][0] === T_WHITESPACE and
				$tokens[$i][0]   === T_STRING and
				$tokens[$i+1][0] === T_WHITESPACE and
				$tokens[$i+2][0] === T_EXTENDS and
				$tokens[$i+3][0] === T_WHITESPACE and
				$tokens[$i+4][0] === T_STRING
			) {
				$inheritedFrom = $tokens[$i+4][1];

				// See if class extends Unitest
				if ($this->_isValidSuiteClass($inheritedFrom)) {
					$classes[] = $tokens[$i][1];
				}

			}
		}

		return $classes;
	}



	/**
	* Go through a list of classes, merge parent classes
	*
	* INPUT
	*	 array('Unitest', 'Alpha', 'Bravo', 'Charlie')
	*
	* OUTPUT
	*	 array(
	*		'Unitest' => array(
	*			'Alpha' => array(
	*				'Bravo' => array(
	*					'Charlie' => array(
	*					),
	*				),
	*			),
	*		),
	*	 )
	*/
	final private function _generateClassMap ($classes) {
		$results = array();

		// Go deeper if there's any children
		if (is_array($classes) and !empty($classes)) {
			$children = $classes;
			$parent = array_shift($children);

			// Recursion for treating children
			$results[$parent] = $this->_generateClassMap($children);

		}

		return $results;
	}



	/**
	* Instantiate suite objects based on class names recursively
	*/
	final private function _generateSuites ($classes, $parent = null) {
		$suites = array();

		// Default to self
		if (!isset($parent)) {
			$parent = $this;
		}

		// Validate parent
		if (!$this->_isValidSuite($parent)) {
			throw new Exception('Invalid parent suite passed as parent.');
		}

		foreach ($classes as $class => $children) {
			$suite = new $class();

			// Recursion
			if (!empty($children)) {
				$this->_generateSuites($children, $suite);
			}

			// Add to own flock
			$parent->child($suite);

		}
		return $this;
	}



	/**
	* Include PHP tests in a file
	*/
	final private function _loadFile ($path) {
		$suites = array();

		if (is_file($path)) {

			// Look for any Unitest classes
			$classes = $this->_classesInScript(file_get_contents($path));

			// Include if found
			if (!empty($classes)) {
				include_once $path;
			}

			// Store class tree
			foreach ($classes as $class) {
				// $suite = new $class();
				$suites[] = array_merge(array_reverse(array_values(class_parents($class))), array($class));
			}

		}

		return $suites;
	}



	/**
	* Find test suites in locations
	*/
	final private function _loadFiles () {
		$suites = array();
		$paths = func_get_args();
		$paths = $this->_flattenArray($paths);

		foreach ($paths as $path) {

			// Path given
			if (is_string($path)) {

				// File
				if (is_file($path)) {
					$suites = array_merge($suites, $this->_loadFile($path));

				// Directory: scrape recursively for all files
				} else if (is_dir($path)) {
					$suites = array_merge($suites, $this->_execute('_loadFiles', $this->_rglobFiles($path)));
				}

			}

		}

		return $suites;
	}



	/**
	* Go through a list of classes, merge parent classes
	*
	* INPUT
	*	 array(
	*		 array(
	*			'Unitest' => array(
	*				'Alpha' => array(
	*					'Bravo' => array(
	*						'Charlie' => array(
	*						),
	*					),
	*				),
	*			),
	*		 ),
	*		 array(
	*			'Unitest' => array(
	*				'Alpha' => array(
	*					'Uniform' => array(
	*					),
	*				),
	*			),
	*		 ),
	*	 )
	*
	* OUTPUT
	*	 array(
	*		'Unitest' => array(
	*			'Alpha' => array(
	*				'Bravo' => array(
	*					'Charlie' => array(),
	*				),
	*				'Uniform' => array(),
	*			),
	*		),
	*	 )
	*/
	final private function _mergeClassMap ($classTrees) {
		$results = array();

		// Array of each
		if (!empty($classTrees)) {
			foreach ($classTrees as $classTree) {
				foreach ($classTree as $name => $children) {

					// New set
					if (!isset($results[$name])) {
						$results[$name] = array();
					}

					// Collect all children here
					$results[$name][] = $children;

				}
			}

			// Organize children
			foreach ($results as $key => $value) {
				if (empty($value)) {
					$results[$key] = array();
				} else if (count($value) === 1) {
					$results[$key] = $value[0];
				} else {
					$results[$key] = $this->_mergeClassMap($value);
				}
			}

		}

		// Sort by key
		ksort($results);

		return $results;
	}



	/**
	* Aliases
	*/


	final  protected function shouldBeBool ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeBoolean'), $arguments);
	}

	final  protected function shouldBeInt ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeInteger'), $arguments);
	}

	final  protected function shouldBeDouble ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeFloat'), $arguments);
	}

	final  protected function should_be_bool ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeBoolean'), $arguments);
	}

	final  protected function should_be_int ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeInteger'), $arguments);
	}

	final  protected function should_be_double ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeFloat'), $arguments);
	}

	final  protected function should_be_equal ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeEqual'), $arguments);
	}

	final  protected function should_not ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldNot'), $arguments);
	}

	final  protected function should_not_be_equal ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldNotBeEqual'), $arguments);
	}

	final  protected function should_be_available_class ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeAvailableClass'), $arguments);
	}

	final  protected function should_be_of_class ($object, $class) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeOfClass'), $arguments);
	}

	final  protected function should_extend_class ($objectOrClass, $targetClass) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldExtendClass'), $arguments);
	}

	final  protected function should_be_directory ($path) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeDirectory'), $arguments);
	}

	final  protected function should_be_file ($path) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeFile'), $arguments);
	}

	final  protected function should_be_file_or_directory ($path) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeFileOrDirectory'), $arguments);
	}

	final  protected function should_be_included_file ($path) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeIncludedFile'), $arguments);
	}

	final  protected function should_not_be_file_or_directory ($path) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldNotBeFileOrDirectory'), $arguments);
	}

	final  protected function should_have_abstract_method ($objectOrClass, $method) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveAbstractMethod'), $arguments);
	}

	final  protected function should_have_final_method ($objectOrClass, $method) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveFinalMethod'), $arguments);
	}

	final  protected function should_have_method ($objectOrClass, $method) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveMethod'), $arguments);
	}

	final  protected function should_have_private_method ($objectOrClass, $method) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHavePrivateMethod'), $arguments);
	}

	final  protected function should_have_protected_method ($objectOrClass, $method) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveProtectedMethod'), $arguments);
	}

	final  protected function should_have_public_method ($objectOrClass, $method) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHavePublicMethod'), $arguments);
	}

	final  protected function should_have_static_method ($objectOrClass, $method) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveStaticMethod'), $arguments);
	}

	final  protected function should_have_private_property ($objectOrClass, $property) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHavePrivateProperty'), $arguments);
	}

	final  protected function should_have_property ($objectOrClass, $property) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveProperty'), $arguments);
	}

	final  protected function should_have_protected_property ($objectOrClass, $property) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveProtectedProperty'), $arguments);
	}

	final  protected function should_have_public_property ($objectOrClass, $property) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHavePublicProperty'), $arguments);
	}

	final  protected function should_have_static_property ($objectOrClass, $property) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveStaticProperty'), $arguments);
	}

	final  protected function should_be_array ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeArray'), $arguments);
	}

	final  protected function should_be_boolean ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeBoolean'), $arguments);
	}

	final  protected function should_be_float ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeFloat'), $arguments);
	}

	final  protected function should_be_integer ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeInteger'), $arguments);
	}

	final  protected function should_be_null ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeNull'), $arguments);
	}

	final  protected function should_be_object ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeObject'), $arguments);
	}

	final  protected function should_be_string ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeString'), $arguments);
	}

	final  protected function should_be_flat_array ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeFlatArray'), $arguments);
	}

	final  protected function should_have_indexed_keys ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveIndexedKeys'), $arguments);
	}

	final  protected function should_have_numerical_keys ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveNumericalKeys'), $arguments);
	}

	final  protected function should_have_string_keys ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldHaveStringKeys'), $arguments);
	}

	final  protected function should_be_above_zero ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeAboveZero'), $arguments);
	}

	final  protected function should_be_below_zero ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeBelowZero'), $arguments);
	}

	final  protected function should_be_zero ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeZero'), $arguments);
	}

	final  protected function should_be_empty_string ($value) {
		$arguments = func_get_args();
		return call_user_func_array(array($this, 'shouldBeEmptyString'), $arguments);
	}


}

function __UnitestHandleError ($errno, $errstr, $errfile, $errline, array $errcontext) {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	return true;
}

?>