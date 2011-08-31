<?php
/* Leads Test cases generated on: 2011-01-31 15:01:51 : 1296483351*/
App::import('Controller', 'Leads');

class TestLeadsController extends LeadsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class LeadsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.lead', 'app.company', 'app.country', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.offer', 'app.offers_detail', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests');

	function startTest() {
		$this->Leads =& new TestLeadsController();
		$this->Leads->constructClasses();
	}

	function endTest() {
		unset($this->Leads);
		ClassRegistry::flush();
	}

	function testAlllead() {

	}

	function testMyopportunity() {

	}

	function testMylead() {

	}

	function testMonitor() {

	}

	function testMonitordetail() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

	function testAddcontact() {

	}

	function testRemovecontact() {

	}

	function testContactsdetail() {

	}

	function testDocumentsdetail() {

	}

	function testHistory() {

	}

	function testLicensesdetail() {

	}

	function testNotesdetail() {

	}

	function testOrdersdetail() {

	}

	function testRequestsdetail() {

	}

	function testEmailsdetail() {

	}

	function testAddRequest() {

	}

	function testMerge() {

	}

}
?>