<?php

class Document extends AppModel {

    var $name = 'Document';
    var $actsAs = array('Logable');
    var $displayField = 'name';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Lead' => array(
            'className' => 'Lead',
            'foreignKey' => 'lead_id',
        )
    );
}

?>