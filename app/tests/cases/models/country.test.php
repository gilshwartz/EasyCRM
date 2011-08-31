<?php
/* Country Test cases generated on: 2011-06-20 08:31:56 : 1308551516*/
App::import('Model', 'Country');

class CountryTestCase extends CakeTestCase {
	var $fixtures = array('app.country', 'app.company', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.offer', 'app.lead', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.offers_detail');

	function startTest() {
		$this->Country =& ClassRegistry::init('Country');
	}

	function endTest() {
		unset($this->Country);
		ClassRegistry::flush();
	}

}
