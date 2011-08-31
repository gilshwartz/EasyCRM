<?php

class Event extends AppModel {

    var $name = 'Event';
    var $actsAs = array('Logable');
    var $displayField = 'subject';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Owner' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => array('id', 'firstname', 'lastname', 'email'),
            'order' => ''
        )
    );

    var $hasMany = array(
        'EventsContact' => array(
            'className'     => 'EventsContact',
            'foreignKey'    => 'events_id',
            'conditions'    => '',
            'order'    => '',
            'limit'        => '',
            'dependent'=> true
        ),
        'EventsLead' => array(
            'className'     => 'EventsLead',
            'foreignKey'    => 'events_id',
            'conditions'    => '',
            'order'    => '',
            'limit'        => '',
            'dependent'=> true
        ),
        'EventsRequest' => array(
            'className'     => 'EventsRequest',
            'foreignKey'    => 'events_id',
            'conditions'    => '',
            'order'    => '',
            'limit'        => '',
            'dependent'=> true
        ),
        'EventsUser' => array(
            'className'     => 'EventsUser',
            'foreignKey'    => 'events_id',
            'conditions'    => '',
            'order'    => '',
            'limit'        => '',
            'dependent'=> true
        )
    );
}

?>