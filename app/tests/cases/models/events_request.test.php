<?php
/* EventsRequest Test cases generated on: 2011-06-20 08:32:49 : 1308551569*/
App::import('Model', 'EventsRequest');

class EventsRequestTestCase extends CakeTestCase {
	var $fixtures = array('app.events_request', 'app.events', 'app.requests');

	function startTest() {
		$this->EventsRequest =& ClassRegistry::init('EventsRequest');
	}

	function endTest() {
		unset($this->EventsRequest);
		ClassRegistry::flush();
	}

}
