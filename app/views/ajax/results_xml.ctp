<?php
if (isset($tag) && $tag) {
    echo $xml->serialize($results, array('format' => 'tags'));
} else {
    echo $xml->serialize($results);
}
?>