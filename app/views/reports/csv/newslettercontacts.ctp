<?php

$csv->addRow(array('Firstname', 'Lastname', 'Company', 'Country', 'Email', 'Phone', 'Language'));

foreach ($results['Contacts'] as $contact) {
    $emails = unserialize($contact['Contact']['emails']);
    if ($emails === false) {
        $emails[0] = $contact['Contact']['emails'];
        $emails[1] = "";
    }
    $phones = unserialize($contact['Contact']['phones']);
    if ($phones === false) {
        $phones[0] = $contact['Contact']['phones'];
        $phones[1] = "";
    }
    $line['firstname'] =  $contact['Contact']['firstname'];
    $line['lastname'] =  $contact['Contact']['lastname'];
    $line['company'] =  $contact['Contact']['company_name'];
    $line['country'] =  $results['Countries'][$contact['Contact']['country']];
    $line['email'] =  $emails[0];
    $line['phone'] =  $phones[0];
    $line['language'] =  $contact['Contact']['language'];
    $csv->addRow($line);
}
echo $csv->render('export');
?>