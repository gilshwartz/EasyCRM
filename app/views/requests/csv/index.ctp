<?php
$csv->addRow(array('Date', 'Type', 'Product', 'Full name', 'Company', 'Country'));

foreach ($requests as $request) {
    $line['date'] = date('Y-m-d', strtotime($request['Request']['dateIn']));
    $line['type'] = $request['Request']['type'];
    if (isset($request['RequestTrial']['product']) && $request['RequestTrial']['product'] != '')
        $line['product'] = $request['RequestTrial']['product'];
    else if (isset($request['RequestQuote']['product']) && $request['RequestQuote']['product'] != '')
        $line['product'] = $request['RequestQuote']['product'];
    else
        $line['product'] = 'n/a';
    $line['fullname'] = $request['Contact']['fullname'];
    $line['company'] = $request['Contact']['company_name'];
    $line['country'] = $countries[$request['Contact']['country']];
    $csv->addRow($line);
}
echo $csv->render('export');
?>