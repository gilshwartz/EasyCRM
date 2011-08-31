<?php
/* Licenses Test cases generated on: 2011-01-31 15:01:22 : 1296483442*/
App::import('Controller', 'Licenses');

class TestLicensesController extends LicensesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class LicensesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.license', 'app.product', 'app.group', 'app.user', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation');

	function startTest() {
		$this->Licenses =& new TestLicensesController();
		$this->Licenses->constructClasses();
	}

	function endTest() {
		unset($this->Licenses);
		ClassRegistry::flush();
	}

	function testAssign() {

	}

	function testView() {

	}

	function testHistory() {

	}

	function testReset() {

	}

	function testRevoke() {

	}

}
?>