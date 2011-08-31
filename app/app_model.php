<?php
/* SVN FILE: $Id$ */

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppModel extends Model {
    var $actsAs   = array('transaction');
    var $cacheQueries = true;
    
    public function generatePassword($length = 8, $special_chars = false) {
        $minors = true;
        $majors = true;
        $numerics = true;
        $chars = '';
        if ($minors)
            $chars .= "abcdefghijkmnopqrstuvwxyz";
        if ($majors)
            $chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($numerics)
            $chars .= "0123456789";
        if ( $special_chars )
            $chars .= '!@#$%^&*()';

        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;
        while ($i <= $length - 1) {
            $num = rand() % strlen($chars);
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }
}
?>