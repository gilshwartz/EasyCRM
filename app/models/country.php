<?php

class Country extends AppModel {

    var $name = 'Country';
    var $displayField = 'name';
    var $order = "Country.name asc";
    var $hasMany = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'country_id'
        )
    );

}

?>