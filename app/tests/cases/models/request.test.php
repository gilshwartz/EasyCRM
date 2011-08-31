<?php
/* Request Test cases generated on: 2011-06-20 08:36:01 : 1308551761*/
App::import('Model', 'Request');

class RequestTestCase extends CakeTestCase {
	var $fixtures = array('app.request', 'app.user', 'app.group', 'app.license', 'app.product', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.events', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.request_trial', 'app.request_quote', 'app.request_contact', 'app.request_consulting', 'app.request_demo', 'app.events_request');

	function startTest() {
		$this->Request =& ClassRegistry::init('Request');
	}

	function endTest() {
		unset($this->Request);
		ClassRegistry::flush();
	}

	function testGetNbNewRequest() {

	}

	function testGetNbPendingRequest() {

	}

}
