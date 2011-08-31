<?php
/* OffersDetail Test cases generated on: 2011-06-20 08:34:06 : 1308551646*/
App::import('Model', 'OffersDetail');

class OffersDetailTestCase extends CakeTestCase {
	var $fixtures = array('app.offers_detail', 'app.offer', 'app.lead', 'app.company', 'app.country', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests');

	function startTest() {
		$this->OffersDetail =& ClassRegistry::init('OffersDetail');
	}

	function endTest() {
		unset($this->OffersDetail);
		ClassRegistry::flush();
	}

}
