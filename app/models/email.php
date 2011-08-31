<?php

class Email extends AppModel {

    var $name = 'Email';
    var $displayField = 'subject';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(        
        'To' => array(
            'className' => 'Contact',
            'joinTable' => 'emails_tos',
            'foreignKey' => 'emails_id',
            'associationForeignKey' => 'contacts_id',
            'unique' => true,
        ),
        'Cc' => array(
            'className' => 'Contact',
            'joinTable' => 'emails_ccs',
            'foreignKey' => 'emails_id',
            'associationForeignKey' => 'contacts_id',
            'unique' => true,
        ),
    );
    var $belongsTo = array(
        'From' => array(
            'className' => 'Contact',
            'foreignKey' => 'from',
        ),
        'Lead' => array(
            'className' => 'Lead',
            'foreignKey' => 'lead_id',
        )
    );

    var $hasMany = array(
        'Document' => array(
            'className' => 'Document',
            'foreignKey' => 'email_id'
        ),
    );

}
?>