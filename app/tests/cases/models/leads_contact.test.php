<?php
/* LeadsContact Test cases generated on: 2011-06-20 08:33:13 : 1308551593*/
App::import('Model', 'LeadsContact');

class LeadsContactTestCase extends CakeTestCase {
	var $fixtures = array('app.leads_contact', 'app.leads', 'app.contacts');

	function startTest() {
		$this->LeadsContact =& ClassRegistry::init('LeadsContact');
	}

	function endTest() {
		unset($this->LeadsContact);
		ClassRegistry::flush();
	}

}
