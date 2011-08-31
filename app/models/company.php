<?php

class Company extends AppModel {

    var $name = 'Company';
    var $actsAs = array('Logable');
    var $displayField = 'name';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
        'Contact' => array(
            'className' => 'Contact',
            'foreignKey' => 'company_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Lead' => array(
            'className' => 'Lead',
            'foreignKey' => 'company_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    var $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

?>