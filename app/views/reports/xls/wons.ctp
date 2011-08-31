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
        ->setCellValue('A2', "Clients")
        ->getStyle('A2')->getFont()->setSize(14);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight(23);

// Add Column Title
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 4, "Code");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 4, "Opportunity");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 4, "Company");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 4, "Status");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 4, "Manager");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, 4, "Amount");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, 4, "Forecast Close");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4')->getFill()->getStartColor()->setRGB('969696');
$objPHPExcel->setActiveSheetIndex(0)->duplicateStyle($objPHPExcel->setActiveSheetIndex(0)->getStyle('A4'), 'B4:' . $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn() . '4');
for ($j = 1; $j < $i; $j++) {
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($j))->setAutoSize(true);
}

// Add data
$i = 5;
foreach ($results as $lead) {
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, strtoupper(substr(sha1($lead['Lead']['id']), 0, 5)));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $i, $lead['Lead']['name']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $i, $lead['Company']['name']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $i, $lead['Lead']['status']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $i, $lead['User']['fullname']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, $i, $lead['Lead']['amount']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $i, date('Y-m-d', strtotime($lead['Lead']['forecast_close'])));

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