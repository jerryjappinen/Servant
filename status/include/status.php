<?php

/**
* Status helper
*/
class Status {



	/**
	* Paths
	*/
	private $root 			= './';
	private $index 			= 'index.php';
	private $actions 		= 'actions/';
	private $includes 		= 'includes/';
	private $utilities 		= 'utilities/';

	private $constants 		= 'includes/constants/';

	/**
	* Lists
	*/
	private $failList 		= array();
	private $errorList 		= array();
	private $warningList 	= array();
	private $reportList 	= array();



	/**
	* Startup
	*/
	public function init ($root = false) {

		// Allow passing in root
		if (is_string($root)) {
			$this->root = $root;
		}

		// Treat other paths
		foreach (array('index', 'actions', 'includes', 'utilities', 'constants') as $key) {
			$this->$key = $this->root.$this->$key;
		}

		return $this;
	}



	/**
	* Validate core paths: index.php, actions, includes and utilities
	*/
	public function checkCorePaths () {

		// Fail if root is not OK
		if (!is_dir($this->root)) {
			$this->fail('Cannot locate Servant\'s root directory.');

		// Root is OK
		} else {

			// Check other paths
			if (!is_file($this->index)) {
				$this->fail('Cannot locate index.php.');
			} else {
				$this->report('`index.php` found.');
			}
			foreach (array('includes', 'utilities') as $key) {
				if (!is_dir($this->$key)) {
					$this->fail('Cannot locate the `'.$key.'` directory.');
				} else {
					$this->report('`'.$key.'` directory found.');
				}
			}

		}

		return $this;
	}



	/**
	* Validate constants
	*/
	public function checkConstants () {

		// Directory missing
		if (!is_dir($this->constants)) {
			$this->error('The `constants` directory is missing. Servant is missing stuff like default settings, status codes and template format configs.');

		} else {

			// We should have at least one JSON file
			$files = glob_files($this->constants, 'json');
			$fileCount = count($files);
			if (empty($files)) {
				$this->error('There are JSON config files in `'.$this->constants.'`. Servant needs at least one that includes the necessary configs.');

			// JSON files found
			} else {

				$errors = array();

				// Read setting files, report errors
				foreach ($files as $file) {
					$json = suffix(prefix(trim(file_get_contents($file)), '{'), '}');
					$decode = json_decode($json, true);
					if (!is_array($decode)) {
						$errors[] = $file;
					}
				}

				// List files in report
				if (empty($errors)) {
					$this->report($fileCount.' `constants` files found, all of them valid.');

				// Some files were malformed
				} else {
					$this->report($fileCount.' `constants` files found, but '.count($errors).' of them were malformed.');
					foreach ($errors as $file) {
						$this->error('`'.unprefix($file, $this->constants).'` constant JSON is malformed.');
					}
				}

			}

		}

		return $this;
	}



	/**
	* Validate actions
	*/
	public function checkActions () {

		// Directory missing
		if (!is_dir($this->actions)) {
			$this->error('The `actions` directory is missing. Servant cannot generate responses.');

		} else {

			// We should have at least a couple of actions
			$actions = glob_dir($this->actions);
			$actionCount = count($actions);
			if (empty($actions)) {
				$this->error('There are no actions in `'.$this->actions.'`. Servant cannot generate responses.');

			// Only one action
			} else if ($actionCount < 2) {
				$this->warn('There is only one action (`'.basename($actions[0]).'`). Normally you want to have at least an error action and a site action.');

			// Action report
			} else {
				foreach ($actions as $key => $value) {
					$actions[$key] = basename($value);
				}
				$this->report($actionCount.' actions found (`'.limplode('`, `', $actions, '` and `').'`).');
			}

		}

		return $this;
	}



	/**
	* Status interface
	*/

	public function fails () {
		return $this->failList;
	}
	public function errors () {
		return $this->errorList;
	}
	public function warnings () {
		return $this->warningList;
	}
	public function reports () {
		return $this->reportList;
	}

	public function noFails () {
		$list = $this->fails();
		return empty($list);
	}
	public function noErrors () {
		$list = $this->errors();
		return empty($list);
	}
	public function noWarnings () {
		$list = $this->warnings();
		return empty($list);
	}
	public function noReports () {
		$list = $this->reports();
		return empty($list);
	}

	public function hasFails () {
		return !$this->noFails();
	}
	public function hasErrors () {
		return !$this->noErrors();
	}
	public function hasWarnings () {
		return !$this->noWarnings();
	}
	public function hasReports () {
		return !$this->noReports();
	}

	public function failCount () {
		return $this->noFails() ? 0 : count($this->fails());
	}
	public function errorCount () {
		return $this->noErrors() ? 0 : count($this->errors());
	}
	public function warningCount () {
		return $this->noWarnings() ? 0 : count($this->warnings());
	}
	public function reportCount () {
		return $this->noReports() ? 0 : count($this->reports());
	}



	/**
	* Private helpers
	*/

	private function fail ($message) {
		return $this->addToList('failList', $message);
	}
	private function error ($message) {
		return $this->addToList('errorList', $message);
	}
	private function warn ($message) {
		return $this->addToList('warningList', $message);
	}
	private function report ($message) {
		return $this->addToList('reportList', $message);
	}

	private function addToList ($list, $value) {
		array_push($this->$list, $value);
		return $this;
	}

}

?>