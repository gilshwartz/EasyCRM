<?php
/* Contacts Test cases generated on: 2011-01-31 15:01:27 : 1296483387*/
App::import('Controller', 'Contacts');

class TestContactsController extends ContactsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ContactsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.contact', 'app.company', 'app.country', 'app.lead', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.leads', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.events_lead', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.events_contact', 'app.offer', 'app.offers_detail');

	function startTest() {
		$this->Contacts =& new TestContactsController();
		$this->Contacts->constructClasses();
	}

	function endTest() {
		unset($this->Contacts);
		ClassRegistry::flush();
	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testAutocomplete() {

	}

	function testGetlead() {

	}

}
?>