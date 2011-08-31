<?php
/* Role Test cases generated on: 2011-06-20 08:36:07 : 1308551767*/
App::import('Model', 'Role');

class RoleTestCase extends CakeTestCase {
	var $fixtures = array('app.role', 'app.user', 'app.group', 'app.license', 'app.product', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.events', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation', 'app.events_user', 'app.users');

	function startTest() {
		$this->Role =& ClassRegistry::init('Role');
	}

	function endTest() {
		unset($this->Role);
		ClassRegistry::flush();
	}

	function testParentNode() {

	}

}
