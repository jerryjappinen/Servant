<?php

/**
* 
*/
class ServantTestUtilityUrlManipulator extends ServantTest {

	private $manipulator = null;

	// Create a new UrlManipulator for each case
	protected function beforeTest ($servant) {
		$servant->utilities()->load('NewUrlManipulator');
		$this->manipulator = new NewUrlManipulator();
	}

	function testManipulatorAvailable ($servant) {
		return $this->shouldBeOfClass($this->manipulator, 'NewUrlManipulator');
	}



	function testCan ($servant) {
		return $this->shouldBeOfClass($this->manipulator, 'NewUrlManipulator');
	}

}

?>