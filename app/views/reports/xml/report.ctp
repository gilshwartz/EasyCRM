<?php
foreach ($results['requests'] as $key => $contact)
    $results['requests'][$key]['Contact']['country'] = $results['countries'][$contact['Contact']['country']];
unset($results['countries']);
unset($results['groups']);
unset($results['users']);

echo $xml->serialize($results);
?>