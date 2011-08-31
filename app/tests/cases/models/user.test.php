<?php
/* User Test cases generated on: 2011-06-20 08:36:26 : 1308551786*/
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
	var $fixtures = array('app.user', 'app.group', 'app.license', 'app.product', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.events', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users');

	function startTest() {
		$this->User =& ClassRegistry::init('User');
	}

	function endTest() {
		unset($this->User);
		ClassRegistry::flush();
	}

	function testParentNode() {

	}

}
