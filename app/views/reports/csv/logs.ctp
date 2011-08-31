<?php

$csv->addRow(array('Date', 'Title', 'Description'));

foreach ($results as $log) {
    $line['date'] =  date('D, d M Y H:i:s', strtotime($log['Log']['created']));
    $line['title'] =  $log['Log']['title'];
    $line['description'] =  $log['Log']['description'];
    
    $csv->addRow($line);
}
echo $csv->render('export');
?>