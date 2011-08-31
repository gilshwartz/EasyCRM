<?php
/* Lead Test cases generated on: 2011-06-20 08:33:08 : 1308551588*/
App::import('Model', 'Lead');

class LeadTestCase extends CakeTestCase {
	var $fixtures = array('app.lead', 'app.company', 'app.country', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.offer', 'app.offers_detail', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests');

	function startTest() {
		$this->Lead =& ClassRegistry::init('Lead');
	}

	function endTest() {
		unset($this->Lead);
		ClassRegistry::flush();
	}

}
