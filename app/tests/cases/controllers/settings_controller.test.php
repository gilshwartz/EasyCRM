<?php
/* Settings Test cases generated on: 2011-01-31 15:01:57 : 1296483417*/
App::import('Controller', 'Settings');

class TestSettingsController extends SettingsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SettingsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.setting');

	function startTest() {
		$this->Settings =& new TestSettingsController();
		$this->Settings->constructClasses();
	}

	function endTest() {
		unset($this->Settings);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testProduct() {

	}

	function testCountry() {

	}

	function testLicensing() {

	}

	function testOgone() {

	}

}
?>