<?php
/* Services Test cases generated on: 2011-01-31 14:01:40 : 1296482020*/
App::import('Controller', 'Services');

class TestServicesController extends ServicesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ServicesControllerTestCase extends CakeTestCase {
	//var $fixtures = array('app.country', 'app.company', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.offer', 'app.lead', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.offers_detail');

	function startTest() {
		$this->Services =& new TestServicesController();
		$this->Services->constructClasses();
	}

	function endTest() {
		unset($this->Services);
		ClassRegistry::flush();
	}

	function testLicensing() {

	}

        function testLicensingTrialEn() {
            $data = array(
                'firstname' => 'Florian',
                'lastname' => 'Furlanetto',
                'email' => 'florian.furlanetto@planningforce.com',
                'country' => 'belgium',
                'company' => 'ISC',
                'phone' => '+32499489858',
                'installationid' => 'xxxxxxxxxxxxxxxxx',
                'product' => 'SIRIUS',
                'consulting' => 'false'
            );
            $resultat = $this->testAction('/services/licensing/trial', array('form' => $data, 'method' => 'post'));
            debug($resultat);
	}

        function testLicensingTrialFr() {
            $data = array(
                'firstname' => 'Florian',
                'lastname' => 'Furlanetto',
                'email' => 'florian.furlanetto@planningforce.com',
                'country' => 'france',
                'company' => 'ISC',
                'phone' => '+32499489858',
                'installationid' => 'xxxxxxxxxxxxxxxxx',
                'product' => 'SIRIUS',
                'consulting' => 'false'
            );
            $resultat = $this->testAction('/services/licensing/trial', array('form' => $data, 'method' => 'post'));
            debug($resultat);
	}

	function testWebrequest() {

	}

	function testCalendar() {

	}

}
?>