<?php
/* LicensesLead Test cases generated on: 2011-06-20 08:33:40 : 1308551620*/
App::import('Model', 'LicensesLead');

class LicensesLeadTestCase extends CakeTestCase {
	var $fixtures = array('app.licenses_lead', 'app.licenses', 'app.leads');

	function startTest() {
		$this->LicensesLead =& ClassRegistry::init('LicensesLead');
	}

	function endTest() {
		unset($this->LicensesLead);
		ClassRegistry::flush();
	}

}
