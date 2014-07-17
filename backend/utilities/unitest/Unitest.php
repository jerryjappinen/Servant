<?php

/**
* Unitest
*
* A one-class miniature unit testing framework for PHP.
*
* This class is a test suite that can test methods, and contain child suites. It can also search for more test files in the file system and generate suites automatically.
*
*
*
* Version 0.1.0
*
* Released under MIT License
* Authored by Jerry Jäppinen
* http://eiskis.net/
* eiskis@gmail.com
*
* https://bitbucket.org/Eiskis/unitest/
*/
class Unitest {

	final public function dump () {
		$arguments = func_get_args();
		$displayErrors = ini_get('display_errors');
		ini_set('display_errors', '0');
		error_log("\n\n\n".var_export(count($arguments) < 2 ? $arguments[0] : $arguments, true), 0)."\n\n";
		ini_set('display_errors', $displayErrors);
	}



	/**
	* Properties
	*/
	private $propertyBaseClass     = 'Unitest';
	private $propertyPrefix        = 'test';

	private $propertyChildren      = array();
	private $propertyInjections    = array();
	private $propertyParent        = null;


	
	// Magic methods

	/**
	* Initialization
	*
	* Parent suite and script variables can be passed
	*/
	final public function __construct () {
		$this->runInit();
		return $this;
	}

	/**
	* String conversion
	*/
	final public function __toString () {
		return get_class($this);
	}



	// Public getters

	/**
	* Add a suite as a child of this suite
	*/
	final public function baseClass () {
		return $this->propertyBaseClass;
	}

	/**
	* Add a suite as a child of this suite
	*/
	final public function child ($child) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if ($this->isValidSuite($argument)) {

				// Store reference to this in the child
				$argument->parent($this, true);

				// Add to own flock
				$this->propertyChildren[] = $argument;

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
			return $this->execute('child', $arguments);
		}

		// Get
		return $this->propertyChildren;
	}

	/**
	* File where this class is defined in
	*/
	final public function file () {
		$ref = new ReflectionClass($this);
		return $ref->getFileName();
	}

	/**
	* Remove an injectable value
	*/
	final public function eject ($name) {
		$arguments = func_get_args();
		$arguments = $this->flattenArray($arguments);
		foreach ($arguments as $argument) {
			if ($this->isInjection($argument)) {
				unset($this->propertyInjections[$argument]);
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
				$this->propertyInjections[$name] = $value;
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
			return $this->execute('inject', $arguments);
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
	* Find out if injection is available
	*/
	final public function isInjection ($name) {
		$arguments = func_get_args();
		$arguments = $this->flattenArray($arguments);
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
	* Values available for test methods
	*/
	final public function injections () {

		// Set
		$arguments = func_get_args();
		if (!empty($arguments)) {
			return $this->execute('inject', $arguments);
		}

		// Get own injections, bubble
		$results = array();
		if ($this->parent()) {
			$results = array_merge($results, $this->parent()->injections());
		}
		$results = array_merge($results, $this->propertyInjections);	


		return $results;
	}

	/**
	* Line number of the file where this class is defined in
	*/
	final public function lineNumber () {
		$ref = new ReflectionClass($this);
		return $ref->getStartLine();
	}

	/**
	* Name of this suite (i.e. class)
	*/
	final public function name () {
		return get_class($this);
	}

	/**
	* Parent suite
	*/
	final public function parent ($parent = null, $parentKnows = false) {

		// Set
		if (isset($parent)) {

			// Validate parent
			if (!$this->isValidSuite($parent)) {
				throw new Exception('Invalid parent suite passed as parent.');
			} else {

				// Parent case adds this to its flock if needed
				if (!$parentKnows) {
					$parent->child($this);
				}

				// This stores a reference to its dad
				$this->propertyParent = $parent;

			}

			return $this;
		}

		// Get
		return $this->propertyParent;
	}

	/**
	* All parents
	*/
	final public function parents () {
		$parents = array();
		if ($this->parent()) {
			$parents = array_merge($this->parent()->parents(), array($this->parent()->name()));
		}
		return $parents;
	}

	/**
	* Test method prefix
	*/
	final public function prefix () {
		return $this->propertyPrefix;
	}

	/**
	* All test methods of this suite
	*/
	final public function tests () {
		$tests = array();

		// All class methods
		foreach (get_class_methods($this) as $method) {

			// Class methods with the correct prefix
			if (substr($method, 0, strlen($this->prefix())) === $this->prefix()) {

				// Prefixed methods that aren't declared in base class
				$ref = new ReflectionMethod($this, $method);
				$class = $ref->getDeclaringClass();
				if ($class->name !== $this->baseClass()) {
					$tests[] = $method;
				}

			}
		}

		return $tests;
	}



	// Suites and tests

	/**
	* Run tests, some or all
	*/
	final public function run () {
		$arguments = func_get_args();

		$ref = new ReflectionClass($this);

		$results = array(
			'class'    => $this->name(),
			'file'     => $this->file(),
			'line'     => $this->lineNumber(),
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
		$suitesOrTests = $this->flattenArray($arguments);

		// Preparation before suite runs anything (possible exceptions are left uncaught)
		$this->runBeforeTests();

		// Run tests
		foreach ($suitesOrTests as $suiteOrTest) {

			// Child suite
			if ($this->isValidSuite($suiteOrTest)) {
				$childResults = $suiteOrTest->run(array_merge($suiteOrTest->tests(), $suiteOrTest->children()));
				$results['children'][] = $childResults;

				// Iterate counters
				foreach (array('failed', 'passed', 'skipped') as $key) {
					$results[$key] = $results[$key] + $childResults[$key];
					$results['duration'] += $childResults['duration'];
				}

			// Test method
			} else if (is_string($suiteOrTest)) {
				$testResult = $this->runTest($suiteOrTest);
				$results['tests'][] = $testResult;

				// Iterate counters
				$results[$testResult['status']]++;
				$results['duration'] += $testResult['duration'];

			}

		}

		// Clean-up after suite has run everything (exceptions are left uncaught)
		$this->runAfterTests();

		return $results;
	}

	/**
	* Run an individual test method
	*/
	final public function runTest ($method) {
		$injections = array();
		$result = $this->skip();
		$duration = 0;

		if (method_exists($this, $method)) {
			$startTime = microtime(true);

			// Contain exceptions of test method
			try {

				// Take a snapshot of current injections
				$allInjectionsCopy = $this->injections();

				// Preparation method
				$this->runBeforeTest($method);

				// Get innjections to pass to test method
				foreach ($this->methodParameterNames($method) as $parameterName) {
					$injections[] = $this->injection($parameterName);
				}

				// Call test method
				$result = $this->execute($method, $injections);

			// Fail test if there are exceptions
			} catch (Exception $e) {
				$result = $this->fail($this->stringifyException($e));
			}

			// Contain exceptions of clean-up
			try {
				$this->runAfterTest($method);
			} catch (Exception $e) {
				$result = $this->fail($this->stringifyException($e));
			}

			// Restore injections as they were before the test
			$this->propertyInjections = $allInjectionsCopy;

			$duration = microtime(true) - $startTime;
		}

		// Test report
		return array(
			'class'      => $this->name(),
			'duration'   => $this->roundExecutionTime($duration),
			'method'     => $method,
			'file'       => $this->file(),
			'line'       => $this->methodLineNumber($method),
			'status'     => $this->assess($result),
			'message'    => $result,
			'injections' => $injections,
		);
	}

	/**
	* Initialize suites in locations
	*/
	final public function scrape () {
		$arguments = func_get_args();

		// Load classes automatically (arguments passed to loadFiles)
		$classes = $this->execute('loadFiles', $arguments);

		// Treat classes
		foreach ($classes as $key => $values) {
			$classes[$key] = $this->generateClassMap($values);
		}
		$classes = $this->mergeClassMap($classes);

		if (!empty($classes)) {
			$parents = array_reverse(class_parents($this));
			$parents[] = $this->name();

			// Find own class from class map, only generate child suites from own child classes
			foreach ($parents as $parent) {
				if (isset($classes[$parent])) {
					$classes = $classes[$parent];
				} else {
					break;
				}
			}

			// We generate a map of required test suite classes here
			$suites = $this->generateSuites($classes);

		}

		return $this;
	}



	// Event handler methods

	/**
	* When instance is created
	*/
	final private function runInit () {
		$arguments = func_get_args();
		$this->execute('init', $arguments);
		return $this;
	}

	/**
	* When a suite is about to run
	*/
	final private function runBeforeTests () {
		$arguments = func_get_args();
		$this->execute('beforeTests', $arguments);
		return $this;
	}

	/**
	* When a suite has run tests
	*/
	final private function runAfterTests () {
		$arguments = func_get_args();
		$this->execute('afterTests', $arguments);
		return $this;
	}

	/**
	* When a singe test is about to run
	*/
	final private function runBeforeTest ($method) {
		$arguments = func_get_args();
		$this->execute('beforeTest', $arguments);
		return $this;
	}

	/**
	* When a singe test has been run
	*/
	final private function runAfterTest ($method) {
		$arguments = func_get_args();
		$this->execute('afterTest', $arguments);
		return $this;
	}



	// Assertions

	/**
	* Truey
	*/
	final public function should ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if (!$argument) {
				return $this->fail();
			}
		}
		return $this->pass();
	}

	/**
	* Falsey
	*/
	final public function shouldNot ($value) {
		$arguments = func_get_args();
		foreach ($arguments as $argument) {
			if ($argument) {
				return $this->fail();
			}
		}
		return $this->pass();
	}

	/**
	* Equality
	*/
	final public function shouldBeEqual ($value) {
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
	* Non-equality
	*/
	final public function shouldNotBeEqual ($value) {
		$arguments = func_get_args();
		return !$this->execute('shouldBeEqual', $arguments);
	}

	/**
	* Should be of a specific class. Fails if passed non-objects or no objects.
	*/
	final public function shouldBeOfClass ($className, $value) {
		$arguments = func_get_args();
		array_shift($arguments);

		// No objects to test
		if (empty($arguments)) {
			return $this->fail();
		} else {
			foreach ($arguments as $argument) {

				// Not an object
				if (!is_object($argument)) {
					return $this->fail();

				// Wrong class
				} else if (get_class($argument) !== $className) {
					return $this->fail();
				}

			}

		}

		return $this->pass();
	}

	/**
	* Should be of any class that extends a specific class. Fails if passed non-objects or no objects.
	*/
	final public function shouldExtendClass ($className, $value) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all values
		foreach ($arguments as $argument) {

			// Not an object
			if (!is_object($argument)) {
				return $this->fail();

			// Wrong class
			} else if (!is_subclass_of($argument, $className)) {
				return $this->fail();
			}

		}

		return $this->pass();
	}

	/**
	* A property should exist in class or object.
	*/
	final public function shouldHaveProperty ($subject, $property) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given properties
		foreach ($arguments as $argument) {

			// Not an object
			if (!property_exists($subject, $argument)) {
				return $this->fail();
			}

		}

		return $this->pass();
	}

	/**
	* A method should exist in class or object.
	*/
	final public function shouldHaveMethod ($subject, $method) {
		$arguments = func_get_args();
		array_shift($arguments);

		// Test all given properties
		foreach ($arguments as $argument) {

			// Not an object
			if (!method_exists($subject, $argument)) {
				return $this->fail();
			}

		}

		return $this->pass();
	}



	// Return a test result

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
	* Test always passes with true
	*/
	final protected function pass () {
		return true;
	}

	/**
	* Test skipped with null
	*/
	final protected function skip () {
		return true;
	}



	// Assess a value like it was a test result

	final protected function assess ($value) {
		if ($this->passes($value)) {
			return 'passed';
		} else if ($this->skips($value)) {
			return 'skipped';
		}
		return 'failed';
	}

	/**
	* Assess failure
	*/
	final protected function fails ($value) {
		return !($this->passes($value) or $this->skips($value));
	}

	/**
	* Assess pass
	*/
	final protected function passes ($value) {
		return $value === true;
	}

	/**
	* Assess skip
	*/
	final protected function skips ($value) {
		return $value === null;
	}



	// Private helpers: class management

	/**
	* Find out which classes will be defined in a script
	*/
	final private function classesInScript ($code = '') {
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
				if ($this->isValidSuiteClass($inheritedFrom)) {
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
	final private function generateClassMap ($classes) {
		$results = array();

		// Go deeper if there's any children
		if (is_array($classes) and !empty($classes)) {
			$children = $classes;
			$parent = array_shift($children);

			// Recursion for treating children
			$results[$parent] = $this->generateClassMap($children);

		}

		return $results;
	}

	/**
	* Instantiate suite objects based on class names recursively
	*/
	final private function generateSuites ($classes, $parent = null) {
		$suites = array();

		// Default to self
		if (!isset($parent)) {
			$parent = $this;
		}

		// Validate parent
		if (!$this->isValidSuite($parent)) {
			throw new Exception('Invalid parent suite passed as parent.');
		}

		foreach ($classes as $class => $children) {
			$suite = new $class();

			// Recursion
			if (!empty($children)) {
				$this->generateSuites($children, $suite);
			}

			// Add to own flock
			$parent->child($suite);

		}
		return $this;
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
	final private function mergeClassMap ($classTrees) {
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
					$results[$key] = $this->mergeClassMap($value);
				}
			}

		}

		// Sort by key
		ksort($results);

		return $results;
	}



	// Private helpers: specialized tools

	/**
	* Validate a suite object
	*/
	final private function isValidSuite ($case) {
		return isset($case) and is_object($case) and (
			get_class($case) === $this->baseClass() or
			is_subclass_of($case, $this->baseClass())
		);
	}

	/**
	* Validate a suite class
	*/
	final private function isValidSuiteClass ($class) {
		$ref = new ReflectionClass($class);
		if ($class === $this->baseClass() or $ref->isSubclassOf($this->baseClass())) {
			return true;
		}
		return false;
	}

	/**
	* Which line method is defined in within its class file
	*/
	final private function methodLineNumber ($method) {
		$ref = new ReflectionMethod($this, $method);
		return $ref->getStartLine();
	}

	/**
	* Find out which variables a method is expecing
	*/
	final private function methodParameterNames ($method) {
		$results = array();
		$ref = new ReflectionMethod($this, $method);
		foreach ($ref->getParameters() as $parameter) {
			$results[] = $parameter->name;
		}
		return $results;
	}



	// Private helpers: file system

	/**
	* Find directories
	*/
	final private function globDir ($path = '') {

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
	final private function globFiles ($path = '', $filetypes = array()) {
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
	* Include PHP tests in a file
	*/
	final private function loadFile ($path) {
		$suites = array();

		if (is_file($path)) {

			// Look for any Unitest classes
			$classes = $this->classesInScript(file_get_contents($path));

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
	final private function loadFiles () {
		$suites = array();
		$paths = func_get_args();
		$paths = $this->flattenArray($paths);

		foreach ($paths as $path) {

			// Path given
			if (is_string($path)) {

				// File
				if (is_file($path)) {
					$suites = array_merge($suites, $this->loadFile($path));

				// Directory: scrape recursively for all files
				} else if (is_dir($path)) {
					$suites = array_merge($suites, $this->execute('loadFiles', $this->rglobFiles($path)));
				}

			}

		}

		return $suites;
	}

	/**
	* Find files recursively
	*/
	final private function rglobFiles ($path = '', $filetypes = array()) {

		// Accept file type restrictions as a single array or multiple independent values
		$arguments = func_get_args();
		array_shift($arguments);
		$filetypes = $this->flattenArray($arguments);

		// Run glob_files for this directory and its subdirectories
		$files = $this->globFiles($path, $filetypes);
		foreach ($this->globDir($path) as $child) {
			$files = array_merge($files, $this->rglobFiles($child, $filetypes));
		}

		return $files;
	}



	// Private helpers: generic

	/**
	* Run own method with arguments
	*/
	final private function execute ($method, $arguments) {
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
	final private function flattenArray ($array) {
		$result = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = array_merge($result, $this->flattenArray($value));
			} else {
				$result[] = $value;
			}
		}
		return $result;
	}

	/**
	* Round and format time script execution time
	*
	* @param float $microseconds
	*
	* @return float Time in milliseconds, rounded to nine decimal places
	*
	*/
	final private function roundExecutionTime ($microseconds) {
		return $microseconds;
	}

	/**
	* Represent exception as string
	*/
	final private function stringifyException ($e) {
		return ''.$e->getMessage().' ('.$e->getFile().' line '.$e->getLine().', error code '.$e->getCode().')';
	}



}



function __UnitestHandleError ($errno, $errstr, $errfile, $errline, array $errcontext) {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

?>