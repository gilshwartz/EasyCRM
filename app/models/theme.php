<?php

class Theme extends AppModel {

    var $name = 'Theme';
    var $useTable = false;
    
    public function getThemes() {
        $folder = new Folder(ROOT . DS . APP_DIR . DS . "views" . DS . "themed");
        $themes = $folder->read();
        return $themes[0];
    }
    
}

?>