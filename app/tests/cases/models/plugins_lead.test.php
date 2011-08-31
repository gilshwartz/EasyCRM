<?php
/* PluginsLead Test cases generated on: 2011-06-20 08:35:51 : 1308551751*/
App::import('Model', 'PluginsLead');

class PluginsLeadTestCase extends CakeTestCase {
	var $fixtures = array('app.plugins_lead', 'app.plugin', 'app.lead', 'app.company', 'app.country', 'app.contact', 'app.events_contact', 'app.events', 'app.contacts', 'app.leads_contact', 'app.leads', 'app.offer', 'app.offers_detail', 'app.user', 'app.group', 'app.license', 'app.product', 'app.licenses_lead', 'app.licenses', 'app.licenses_operation', 'app.role', 'app.events_user', 'app.users', 'app.events_lead', 'app.leads_document', 'app.documents', 'app.leads_request', 'app.requests');

	function startTest() {
		$this->PluginsLead =& ClassRegistry::init('PluginsLead');
	}

	function endTest() {
		unset($this->PluginsLead);
		ClassRegistry::flush();
	}

	function testGenerateActivationKey() {

	}

}
