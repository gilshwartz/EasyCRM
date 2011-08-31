<?php
/* LeadsDocument Test cases generated on: 2011-06-20 08:33:19 : 1308551599*/
App::import('Model', 'LeadsDocument');

class LeadsDocumentTestCase extends CakeTestCase {
	var $fixtures = array('app.leads_document', 'app.leads', 'app.documents');

	function startTest() {
		$this->LeadsDocument =& ClassRegistry::init('LeadsDocument');
	}

	function endTest() {
		unset($this->LeadsDocument);
		ClassRegistry::flush();
	}

}
