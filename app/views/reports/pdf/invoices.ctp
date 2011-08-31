<?php
App::import('Vendor','xtcpdf');

// create new PDF document
$pdf = new XTCPDF();

// set document information
$pdf->SetCreator("PlanningForce CRM");
$pdf->SetAuthor('PlanningForce CRM');
$pdf->SetTitle('PlanningForce CRM : invoices');
$pdf->SetSubject('PlanningForce CRM : invoices');
$pdf->SetKeywords('Export Invoices list');

// set default header data
$pdf->SetHeaderData("planningforce.png", PDF_HEADER_LOGO_WIDTH, 'PlanningForce CRM', 'Invoices');
$pdf->xfootertext = 'Copyright © %d Intelligent Software Company. All rights reserved.';

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont('freesans', '', 10);

// add a page
$pdf->AddPage();

// print a line using Cell()
//$pdf->Cell(0, 12, 'Its working', 1, 1, 'C');

$html = '<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><b>Order#</b></th>
            <th><b>Company</b></th>
            <th><b>Name</b></th>
            <th><b>VAT excl.</b></th>
            <th><b>Date</b></th>
        </tr>
    </thead><tbody>';

    foreach ($results as $offer) {
        $html .= '<tr>';

        $html .= '<td>'.$offer['Offer']['invoice_id'].'</td>';
        $html .= '<td>'.$offer['Offer']['billing_company'].'</td>';
        $html .= '<td>'.$offer['Offer']['billing_name'].'</td>';
        $amount = 0;
        foreach ($offer['OffersDetail'] as $detail) {
            $amount = $detail['amount'] + $amount;
        }
        $html .= '<td>'.$offer['Offer']['currency'].' '.$amount.'</td>';
        $html .= '<td>'.date('Y-m-d', strtotime($offer['Offer']['date_paid'])).'</td>';
        $html .= '<td>';
        $html .= '<button class="button" onclick="offerView(' . $offer['Offer']['id'] . ');">View details</button>';
        $html .= '</td>';
        $html .= '</tr>';
    }
$html .= '</tbody></table>';


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, 'L');


//Close and output PDF document
$pdf->Output('filename.pdf', 'I');
?>