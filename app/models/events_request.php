<?php

class EventsRequest extends AppModel {

    var $name = 'EventsRequest';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Events' => array(
            'className' => 'Events',
            'foreignKey' => 'events_id',
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