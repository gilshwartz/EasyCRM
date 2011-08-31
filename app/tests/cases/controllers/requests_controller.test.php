<?php
/* Requests Test cases generated on: 2011-01-31 15:01:07 : 1296483427*/
App::import('Controller', 'Requests');

class TestRequestsController extends RequestsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class RequestsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.request', 'app.user', 'app.group', 'app.license', 'app.product', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.events', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.request_trial', 'app.request_quote', 'app.request_contact', 'app.request_consulting', 'app.request_demo', 'app.events_request');

	function startTest() {
		$this->Requests =& new TestRequestsController();
		$this->Requests->constructClasses();
	}

	function endTest() {
		unset($this->Requests);
		ClassRegistry::flush();
	}

	function testNewrequest() {

	}

	function testMyrequest() {

	}

	function testView() {

	}

	function testDelete() {

	}

	function testEdit() {

	}

	function testNewest() {

	}

	function testPending() {

	}

}
?>