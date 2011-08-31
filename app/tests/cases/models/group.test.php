<?php
/* Group Test cases generated on: 2011-06-20 08:33:02 : 1308551582*/
App::import('Model', 'Group');

class GroupTestCase extends CakeTestCase {
	var $fixtures = array('app.group', 'app.user', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.license', 'app.product', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation');

	function startTest() {
		$this->Group =& ClassRegistry::init('Group');
	}

	function endTest() {
		unset($this->Group);
		ClassRegistry::flush();
	}

}
