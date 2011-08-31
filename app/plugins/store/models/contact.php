<?php

class Contact extends StoreAppModel {

    var $name = 'Contact';
    var $actAs = array('Search.Searchable');
    var $displayField = 'fullname';
    var $virtualFields = array(
        'fullname' => 'CONCAT(Contact.firstname, " ", Contact.lastname)'
    );
    public $filterArgs = array(
        array('name' => 'firstname', 'type' => 'like'),
        array('name' => 'lastname', 'type' => 'like')
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'company_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'EventsContact' => array(
            'className' => 'EventsContact',
            'foreignKey' => 'contacts_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => true
        ),
        'LeadsContact' => array(
            'className' => 'LeadsContact',
            'foreignKey' => 'contacts_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => true
        ),
        'Offer' => array(
            'className' => 'Offer',
            'foreignKey' => 'contact',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => true
        )
    );
}

?>