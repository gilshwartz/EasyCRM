<?php
foreach ($results['Contacts'] as $key => $contact)
    $results['Contacts'][$key]['Contact']['country'] = $results['Countries'][$contact['Contact']['country']];
unset($results['Countries']);

echo $xml->serialize($results);
?>