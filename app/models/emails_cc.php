<?php

class EmailsCc extends AppModel {

    var $name = 'EmailsCc';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Emails' => array(
            'className' => 'Email',
            'foreignKey' => 'emails_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Contacts' => array(
            'className' => 'Contact',
            'foreignKey' => 'contacts_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

?>