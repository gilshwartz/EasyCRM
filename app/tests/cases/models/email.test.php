<?php
/* Email Test cases generated on: 2011-06-20 08:32:10 : 1308551530*/
App::import('Model', 'Email');

class EmailTestCase extends CakeTestCase {
	var $fixtures = array('app.email', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.leads', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.events_lead', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.document', 'app.emails_to', 'app.emails_cc');

	function startTest() {
		$this->Email =& ClassRegistry::init('Email');
	}

	function endTest() {
		unset($this->Email);
		ClassRegistry::flush();
	}

}
