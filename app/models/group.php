<?php

class Group extends AppModel {

    var $name = 'Group';
    var $displayField = 'name';
    var $actsAs = array('Tree');
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'ParentGroup' => array(
            'className' => 'Group',
            'foreignKey' => 'parent_id'
        )
    );
    var $hasMany = array(
        'ChildGroup' => array(
            'className' => 'Group',
            'foreignKey' => 'parent_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'group_id'
        ),
        'License' => array(
            'className' => 'License',
            'foreignKey' => 'group_id'
        )
    );
}

?>