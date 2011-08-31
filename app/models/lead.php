<?php

class Lead extends AppModel {

    var $name = 'Lead';
    var $actsAs = array('Logable');
    var $displayField = 'name';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'company_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );
    var $hasMany = array(
        'EventsLead' => array(
            'className' => 'EventsLead',
            'foreignKey' => 'leads_id'
        ),
        'LeadsContact' => array(
            'className' => 'LeadsContact',
            'foreignKey' => 'leads_id'
        ),
        'Documents' => array(
            'className' => 'Document',
            'foreignKey' => 'lead_id'
        ),
        'LeadsRequest' => array(
            'className' => 'LeadsRequest',
            'foreignKey' => 'leads_id'
        ),
        'LicensesLead' => array(
            'className' => 'LicensesLead',
            'foreignKey' => 'leads_id'
        )
    );
}

?>