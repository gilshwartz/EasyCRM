<?php
/* Event Test cases generated on: 2011-06-20 08:32:33 : 1308551553*/
App::import('Model', 'Event');

class EventTestCase extends CakeTestCase {
	var $fixtures = array('app.event', 'app.user', 'app.group', 'app.license', 'app.product', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.events', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_request');

	function startTest() {
		$this->Event =& ClassRegistry::init('Event');
	}

	function endTest() {
		unset($this->Event);
		ClassRegistry::flush();
	}

}
