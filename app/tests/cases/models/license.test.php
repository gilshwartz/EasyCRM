<?php
/* License Test cases generated on: 2011-06-20 08:33:35 : 1308551615*/
App::import('Model', 'License');

class LicenseTestCase extends CakeTestCase {
	var $fixtures = array('app.license', 'app.product', 'app.group', 'app.user', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation');

	function startTest() {
		$this->License =& ClassRegistry::init('License');
	}

	function endTest() {
		unset($this->License);
		ClassRegistry::flush();
	}

	function testResetLicense() {

	}

	function testRevokeLicense() {

	}

	function testAssignLicense() {

	}

	function testGetUnactivated() {

	}

	function testBuildPullQuery() {

	}

	function testGetActivation() {

	}

	function testGetTry() {

	}

	function testPushActivation() {

	}

	function testPushActivationTry() {

	}

}
