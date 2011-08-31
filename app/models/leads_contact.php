<?php

class LeadsContact extends AppModel {

    var $name = 'LeadsContact';
    var $actsAs = array('Logable');
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Leads' => array(
            'className' => 'Leads',
            'foreignKey' => 'leads_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Contacts' => array(
            'className' => 'Contacts',
            'foreignKey' => 'contacts_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

?>