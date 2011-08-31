<?php

class EventsLead extends AppModel {

    var $name = 'EventsLead';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Events' => array(
            'className' => 'Events',
            'foreignKey' => 'events_id',
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