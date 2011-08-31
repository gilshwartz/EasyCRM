<?php
App::import('Vendor', 'PHPExcel', array('file' => 'excel/PHPExcel.php'));

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objPHPExcel->getDefaultStyle()->getFont()->setName('Verdana');

// Set Properties
$objPHPExcel->getProperties()->setCreator("PlanningForce CRM")
        ->setLastModifiedBy("PlanningForce CRM")
        ->setTitle("PlanningForce CRM : Trial requests export")
        ->setSubject("PlanningForce CRM : Trial requests export")
        ->setDescription("PlanningForce CRM : Trial requests export")
        ->setKeywords("")
        ->setCategory("");


// Add Document Title
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A2', ucfirst($results['type']))
        ->getStyle('A2')->getFont()->setSize(14);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight(23);

// Add Column Title
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 4, "Date");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 4, "Type");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 4, "Status");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 4, "Product");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 4, "Full Name");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, 4, "Company");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, 4, "Email");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, 4, "Country");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4')->getFill()->getStartColor()->setRGB('969696');
$objPHPExcel->setActiveSheetIndex(0)->duplicateStyle($objPHPExcel->setActiveSheetIndex(0)->getStyle('A4'), 'B4:' . $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn() . '4');
for ($j = 1; $j < $i; $j++) {
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($j))->setAutoSize(true);
}

// Add data
$i = 5;
foreach ($results['requests'] as $request) {
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, date('Y-m-d', strtotime($request['Request']['dateIn'])));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $i, $request['Request']['type']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $i, $request['Request']['status']);
    if (isset($request['RequestTrial']['product']) && $request['RequestTrial']['product'] != '') {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $i, $request['RequestTrial']['product']);
    } else if (isset($request['RequestQuote']['product']) && $request['RequestQuote']['product'] != '') {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $i, $request['RequestQuote']['product']);
    } else {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $i, "n/a");
    }
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $i, $request['Contact']['firstname'] . ' ' . $request['Contact']['lastname']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, $i, $request['Contact']['company_name']);
    
    $emails = unserialize($request['Contact']['emails']);
    if (!$emails)
        $emails[0] = $request['Contact']['emails'];
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $i, $emails[0]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $i, $results['countries'][$request['Contact']['country']]);

    $i++;
}


// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="export.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>