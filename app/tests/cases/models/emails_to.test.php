<?php
/* EmailsTo Test cases generated on: 2011-06-20 08:32:22 : 1308551542*/
App::import('Model', 'EmailsTo');

class EmailsToTestCase extends CakeTestCase {
	var $fixtures = array('app.emails_to', 'app.email', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.leads', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.events_lead', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.document', 'app.emails_cc');

	function startTest() {
		$this->EmailsTo =& ClassRegistry::init('EmailsTo');
	}

	function endTest() {
		unset($this->EmailsTo);
		ClassRegistry::flush();
	}

}
