<?php
class LicensesLead extends StoreAppModel {
	var $name = 'LicensesLead';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Licenses' => array(
			'className' => 'Licenses',
			'foreignKey' => 'licenses_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Leads' => array(
			'className' => 'Leads',
			'foreignKey' => 'leads_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>