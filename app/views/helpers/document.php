<?php

Class DocumentHelper extends AppHelper {

    /**
     * @brief Make a human file size
     * @param $bytes file size in bytes
     * @returns a human readable file size
     *
     * Makes 2048 to 2 kB.
     */
    
    public static function humanFileSize($bytes) {
        if ($bytes < 1024) {
            return "$bytes B";
        }
        $bytes = round($bytes / 1024, 1);
        if ($bytes < 1024) {
            return "$bytes kB";
        }
        $bytes = round($bytes / 1024, 1);
        if ($bytes < 1024) {
            return "$bytes MB";
        }

        // Wow, heavy duty for owncloud
        $bytes = round($bytes / 1024, 1);
        return "$bytes GB";
    }

}
?>