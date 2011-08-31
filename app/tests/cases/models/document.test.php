<?php
/* Document Test cases generated on: 2011-06-20 08:32:03 : 1308551523*/
App::import('Model', 'Document');

class DocumentTestCase extends CakeTestCase {
	var $fixtures = array('app.document', 'app.leads_document', 'app.leads', 'app.documents');

	function startTest() {
		$this->Document =& ClassRegistry::init('Document');
	}

	function endTest() {
		unset($this->Document);
		ClassRegistry::flush();
	}

}
