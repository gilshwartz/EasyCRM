<?php
class LicensesOperation extends AppModel {
	var $name = 'LicensesOperation';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'License' => array(
			'className' => 'License',
			'foreignKey' => 'licenses_id',
			'fields' => '',
			'order' => ''
		)
	);
}
?>