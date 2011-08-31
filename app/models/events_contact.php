<?php

class EventsContact extends AppModel {

    var $name = 'EventsContact';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Events' => array(
            'className' => 'Events',
            'foreignKey' => 'events_id',
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