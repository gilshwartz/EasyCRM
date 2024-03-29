<?php

class EventsUser extends AppModel {

    var $name = 'EventsUser';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Events' => array(
            'className' => 'Events',
            'foreignKey' => 'events_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Users' => array(
            'className' => 'Users',
            'foreignKey' => 'users_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

?>