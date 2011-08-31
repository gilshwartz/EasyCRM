<?php
/* Documents Test cases generated on: 2011-01-31 15:01:36 : 1296483396*/
App::import('Controller', 'Documents');

class TestDocumentsController extends DocumentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DocumentsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.document', 'app.leads_document', 'app.leads', 'app.documents');

	function startTest() {
		$this->Documents =& new TestDocumentsController();
		$this->Documents->constructClasses();
	}

	function endTest() {
		unset($this->Documents);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testAdd() {

	}

	function testView() {

	}

	function testDelete() {

	}

}
?>