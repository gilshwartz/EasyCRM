<?php
/* Offers Test cases generated on: 2011-01-31 15:01:19 : 1296483139*/
App::import('Controller', 'Offers');

class TestOffersController extends OffersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class OffersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.offer', 'app.lead', 'app.company', 'app.country', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.offers_detail');

	function startTest() {
		$this->Offers =& new TestOffersController();
		$this->Offers->constructClasses();
	}

	function endTest() {
		unset($this->Offers);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testAdd() {

	}

	function testAdd1() {

	}

	function testAdd2() {

	}

	function testAdd3() {

	}

	function testView() {

	}

	function testDelete() {

	}

}
?>