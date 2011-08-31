<?php
/* EventsUser Test cases generated on: 2011-06-20 08:32:55 : 1308551575*/
App::import('Model', 'EventsUser');

class EventsUserTestCase extends CakeTestCase {
	var $fixtures = array('app.events_user', 'app.events', 'app.users');

	function startTest() {
		$this->EventsUser =& ClassRegistry::init('EventsUser');
	}

	function endTest() {
		unset($this->EventsUser);
		ClassRegistry::flush();
	}

}
