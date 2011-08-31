<?php
/* Product Test cases generated on: 2011-06-20 08:35:55 : 1308551755*/
App::import('Model', 'Product');

class ProductTestCase extends CakeTestCase {
	var $fixtures = array('app.product', 'app.license', 'app.group', 'app.user', 'app.role', 'app.events_user', 'app.events', 'app.users', 'app.contact', 'app.company', 'app.country', 'app.lead', 'app.events_lead', 'app.leads', 'app.leads_contact', 'app.contacts', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests', 'app.licenses_lead', 'app.licenses', 'app.events_contact', 'app.offer', 'app.offers_detail', 'app.licenses_operation');

	function startTest() {
		$this->Product =& ClassRegistry::init('Product');
	}

	function endTest() {
		unset($this->Product);
		ClassRegistry::flush();
	}

}
