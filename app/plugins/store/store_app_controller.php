<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class StoreAppController extends Controller {

    function __construct() {
        parent::__construct();
    }

    function beforeFilter() {
        // Desable ACL for Dev
        $this->Auth->allow(array('*'));
    }

}

?>
