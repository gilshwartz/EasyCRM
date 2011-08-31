<?php
/* LicensesOperation Test cases generated on: 2011-06-20 08:33:45 : 1308551625*/
App::import('Model', 'LicensesOperation');

class LicensesOperationTestCase extends CakeTestCase {
	var $fixtures = array('app.licenses_operation', 'app.license', 'app.product', 'app.group', 'app.user', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail');

	function startTest() {
		$this->LicensesOperation =& ClassRegistry::init('LicensesOperation');
	}

	function endTest() {
		unset($this->LicensesOperation);
		ClassRegistry::flush();
	}

}
