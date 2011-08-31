<?php
/* EmailsCc Test cases generated on: 2011-06-20 08:32:16 : 1308551536*/
App::import('Model', 'EmailsCc');

class EmailsCcTestCase extends CakeTestCase {
	var $fixtures = array('app.emails_cc', 'app.email', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.leads', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.events_lead', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.document', 'app.emails_to');

	function startTest() {
		$this->EmailsCc =& ClassRegistry::init('EmailsCc');
	}

	function endTest() {
		unset($this->EmailsCc);
		ClassRegistry::flush();
	}

}
