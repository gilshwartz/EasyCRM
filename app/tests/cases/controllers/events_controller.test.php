<?php
/* Events Test cases generated on: 2011-01-31 15:01:07 : 1296483367*/
App::import('Controller', 'Events');

class TestEventsController extends EventsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class EventsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.event', 'app.user', 'app.group', 'app.license', 'app.product', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.events', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_request');

	function startTest() {
		$this->Events =& new TestEventsController();
		$this->Events->constructClasses();
	}

	function endTest() {
		unset($this->Events);
		ClassRegistry::flush();
	}

	function testMytask() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testSetdone() {

	}

	function testDelete() {

	}

}
?>