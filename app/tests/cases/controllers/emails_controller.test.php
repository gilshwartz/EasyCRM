<?php
/* Emails Test cases generated on: 2011-01-31 15:01:58 : 1296483358*/
App::import('Controller', 'Emails');

class TestEmailsController extends EmailsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class EmailsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.email', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.leads', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.events_lead', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.document', 'app.emails_to', 'app.emails_cc');

	function startTest() {
		$this->Emails =& new TestEmailsController();
		$this->Emails->constructClasses();
	}

	function endTest() {
		unset($this->Emails);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testDelete() {

	}

	function testLink2lead() {

	}

	function testFetch() {

	}

}
?>