<?php

class Country extends StoreAppModel {

    var $name = 'Country';
    var $displayField = 'name';
    var $hasMany = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'country_id'
        )
    );

}

?>