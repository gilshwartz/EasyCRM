<?php
/* EventsLead Test cases generated on: 2011-06-20 08:32:44 : 1308551564*/
App::import('Model', 'EventsLead');

class EventsLeadTestCase extends CakeTestCase {
	var $fixtures = array('app.events_lead', 'app.events', 'app.leads');

	function startTest() {
		$this->EventsLead =& ClassRegistry::init('EventsLead');
	}

	function endTest() {
		unset($this->EventsLead);
		ClassRegistry::flush();
	}

}
