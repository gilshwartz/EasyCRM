<?php

class Role extends AppModel {

    var $name = 'Role';
    var $displayField = 'name';
    var $actsAs = array('Acl' => array('type' => 'requester'));
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(        
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'group_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    function parentNode() {
        return null;
    }

}

?>