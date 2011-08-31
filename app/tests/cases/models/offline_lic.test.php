<?php
/* OfflineLic Test cases generated on: 2011-06-20 08:35:06 : 1308551706*/
App::import('Model', 'OfflineLic');

class OfflineLicTestCase extends CakeTestCase {
	var $fixtures = array('app.offers_detail', 'app.offer', 'app.lead', 'app.company', 'app.country', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.offline_lic');

	function startTest() {
		$this->OfflineLic =& ClassRegistry::init('OfflineLic');
	}

	function endTest() {
		unset($this->OfflineLic);
		ClassRegistry::flush();
	}

}
