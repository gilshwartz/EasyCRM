<?php
header('Content-type: ' . $file['Document']['type']);
if(!isset($inpage)) header('Content-Disposition: attachment; filename="'.$file['Document']['name'].'"');
echo $content_for_layout;
die();
?>