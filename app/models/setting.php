<?php

class Setting extends AppModel {

    var $name = 'setting';
    var $actsAs = array('Logable');
    var $displayField = 'type';
}

?>