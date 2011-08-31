<?php
/* EventsContact Test cases generated on: 2011-06-20 08:32:38 : 1308551558*/
App::import('Model', 'EventsContact');

class EventsContactTestCase extends CakeTestCase {
	var $fixtures = array('app.events_contact', 'app.events', 'app.contacts');

	function startTest() {
		$this->EventsContact =& ClassRegistry::init('EventsContact');
	}

	function endTest() {
		unset($this->EventsContact);
		ClassRegistry::flush();
	}

}
