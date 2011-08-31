<?php

$csv->addRow(array('Code', 'Opportunity', 'Company', 'Status', 'Manager', 'Amount', 'Forecast Close'));

foreach ($results as $lead) {
    $line['code'] = strtoupper(substr(sha1($lead['Lead']['id']), 0, 5));
    $line['name'] = $lead['Lead']['name'];
    $line['company'] = $lead['Company']['name'];
    $line['status'] = $lead['Lead']['status'];
    $line['manager'] = $lead['User']['fullname'];
    $line['amount'] = $lead['Lead']['amount'];
    $line['forecast_close'] = date('Y-m-d', strtotime($lead['Lead']['forecast_close']));
    
    $csv->addRow($line);
}
echo $csv->render('export');
?>