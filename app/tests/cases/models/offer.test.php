<?php
/* Offer Test cases generated on: 2011-06-20 08:34:01 : 1308551641*/
App::import('Model', 'Offer');

class OfferTestCase extends CakeTestCase {
	var $fixtures = array('app.offer', 'app.lead', 'app.company', 'app.country', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.offers_detail');

	function startTest() {
		$this->Offer =& ClassRegistry::init('Offer');
	}

	function endTest() {
		unset($this->Offer);
		ClassRegistry::flush();
	}

	function testGetVat() {

	}

	function testGetAmount() {

	}

	function testCreateOrderRef() {

	}

}
