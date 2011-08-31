<?php
/* Theme Test cases generated on: 2011-06-20 08:36:21 : 1308551781*/
App::import('Model', 'Theme');

class ThemeTestCase extends CakeTestCase {
	var $fixtures = array('app.theme');

	function startTest() {
		$this->Theme =& ClassRegistry::init('Theme');
	}

	function endTest() {
		unset($this->Theme);
		ClassRegistry::flush();
	}

	function testGetTheme() {

	}

}
