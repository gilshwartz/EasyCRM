<?php

class LeadsRequest extends AppModel {

    var $name = 'LeadsRequest';
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
        'Requests' => array(
            'className' => 'Requests',
            'foreignKey' => 'requests_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

?>