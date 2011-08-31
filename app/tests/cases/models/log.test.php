<?php
/* Log Test cases generated on: 2011-06-20 08:33:55 : 1308551635*/
App::import('Model', 'Log');

class LogTestCase extends CakeTestCase {
	var $fixtures = array('app.log');

	function startTest() {
		$this->Log =& ClassRegistry::init('Log');
	}

	function endTest() {
		unset($this->Log);
		ClassRegistry::flush();
	}

}
