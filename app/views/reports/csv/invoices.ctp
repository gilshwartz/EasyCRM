<?php

$csv->addRow(array('Order#', 'Company', 'Name', 'VAT excl.', 'Date'));

foreach ($results as $offer) {
    $line['id'] = $offer['Offer']['invoice_id'];
    $line['company'] = $offer['Offer']['billing_company'];
    $line['name'] = $offer['Offer']['billing_name'];
    $amount = 0;
    foreach ($offer['OffersDetail'] as $detail) {
        $amount = $detail['amount'] + $amount;
    }
    $line['amount'] = $offer['Offer']['currency'].' '.$amount;
    $line['date'] = date('Y-m-d', strtotime($offer['Offer']['date_paid']));

    $csv->addRow($line);
}
echo $csv->render('export');
?>