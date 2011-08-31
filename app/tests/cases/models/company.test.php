<?php
/* Company Test cases generated on: 2011-06-20 08:42:21 : 1308552141*/
App::import('Model', 'Company');

class CompanyTestCase extends CakeTestCase {
	var $fixtures = array('app.company', 'app.country', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.offer', 'app.lead', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.offers_detail');

	function startTest() {
		$this->Company =& ClassRegistry::init('Company');
	}

	function endTest() {
		unset($this->Company);
		ClassRegistry::flush();
	}

}
