<?php
/* LeadsRequest Test cases generated on: 2011-06-20 08:33:28 : 1308551608*/
App::import('Model', 'LeadsRequest');

class LeadsRequestTestCase extends CakeTestCase {
	var $fixtures = array('app.leads_request', 'app.leads', 'app.requests');

	function startTest() {
		$this->LeadsRequest =& ClassRegistry::init('LeadsRequest');
	}

	function endTest() {
		unset($this->LeadsRequest);
		ClassRegistry::flush();
	}

}
