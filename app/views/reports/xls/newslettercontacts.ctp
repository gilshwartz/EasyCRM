<?php
App::import('Vendor', 'PHPExcel', array('file' => 'excel/PHPExcel.php'));

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objPHPExcel->getDefaultStyle()->getFont()->setName('Verdana');

// Set Properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");


// Add Document Title
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A2', 'Newsletter Members')
        ->getStyle('A2')->getFont()->setSize(14);
$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight(23);

// Add Column Title
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 4, "Firstname");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 4, "Lastname");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 4, "Company");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 4, "Country");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 4, "Email");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, 4, "Phone");
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, 4, "Language");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4')->getFill()->getStartColor()->setRGB('969696');
$objPHPExcel->setActiveSheetIndex(0)->duplicateStyle($objPHPExcel->setActiveSheetIndex(0)->getStyle('A4'), 'B4:' . $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn() . '4');
for ($j = 1; $j < $i; $j++) {
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($j))->setAutoSize(true);
}

// Add data
$i = 5;
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
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $contact['Contact']['firstname']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $i, $contact['Contact']['lastname']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, $i, $contact['Contact']['company_name']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, $i, $results['Countries'][$contact['Contact']['country']]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $i, $emails[0]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, $i, $phones[0]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $i, $contact['Contact']['language']);

    $i++;
}


// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="newsletter.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>