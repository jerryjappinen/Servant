<?php

// Generic development helpers
class ServantDev extends ServantObject {

	// All paths in all formats
	public function paths () {
		$results = array();
		$keys = array(
			'documentRoot',
			'root',
			'sites',
			'index',
			'classes',
			'helpers',
			'settings',
			'templates',
			'themes',
			'utilities',
		);
		foreach ($keys as $key => $method) {
			$results[$method] = array(
				'plain' => $this->servant()->paths()->$method(),
				'domain' => $this->servant()->paths()->$method('domain'),
				'server' => $this->servant()->paths()->$method('server'),
			);
		}
		return $results;
	}

}

?>