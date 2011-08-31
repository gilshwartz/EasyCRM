<?php
/* Plugin Test cases generated on: 2011-06-20 08:35:46 : 1308551746*/
App::import('Model', 'Plugin');

class PluginTestCase extends CakeTestCase {
	var $fixtures = array('app.plugin');

	function startTest() {
		$this->Plugin =& ClassRegistry::init('Plugin');
	}

	function endTest() {
		unset($this->Plugin);
		ClassRegistry::flush();
	}

}
