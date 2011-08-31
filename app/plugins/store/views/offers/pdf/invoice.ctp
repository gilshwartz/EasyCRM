<?php

App::import('Vendor', 'xtcpdf');

// create new PDF document
$pdf = new XTCPDF();

// set document information
$pdf->SetCreator("Intelligent Software Company");
$pdf->SetAuthor('Intelligent Software Company');
$pdf->SetTitle('Invoice ' . $offer['Offer']['invoice_id']);
$pdf->SetSubject('Invoice');
$pdf->SetKeywords('Invoice');

// set default header data
$pdf->SetHeaderData("planningforce.png", PDF_HEADER_LOGO_WIDTH, 'Intelligent Software Company', '');
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

// Generate Invoice details
// Create the Invoice HEADER
$html = '<h3>Invoice</h3>';
$html .= '<h4>Ref. ' . $offer['Offer']['invoice_id'] . '</h4>';
$pdf->writeHTMLCell(0, 0, 15, 35, $html, false, 0, 0, 0, 'L');
$html = '<strong>ISC - PlanningForce</strong><br/>
                    Chauss&eacute;e de Nivelles, 121/2<br/>
                    7181 Arquennes - Belgium<br/>
                    <strong>Phone:</strong> +32 67 550 224<br/>
                    <strong>Email:</strong> <a href="mailto:sales@planningforce.com">sales@planningforce.com</a>
                    <br  /><strong>V.A.T:</strong> BE 0473.176.292';
$pdf->writeHTMLCell(0, 0, 20, 20, $html, false, 0, 0, 0, 'R');

// CREATE THE INVOICE CLIENT DETAILS
$html = '<table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0" class="tableinside">
                        <tr>
                            <td><strong>Order Reference</strong></td>
                            <td>' . $offer['Offer']['order_id'] . '</td>
                        </tr>
                        <tr>
                            <td><strong>Company</strong></td>
                            <td>' . $offer['Offer']['billing_company'] . '</td>
                        </tr>
                        <tr>
                            <td><strong>Name</strong></td>
                            <td>' . $offer['Offer']['billing_name'] . '</td>
                        </tr>
                        <tr>
                            <td><strong>VAT number</strong></td>
                            <td>' . $offer['Offer']['billing_vat'] . '</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table cellspacing="0" cellpadding="0" class="tableinside">
                        <tr>
                            <td><strong>Date of payment</strong></td>
                            <td>' . $offer['Offer']['date_paid'] . '</td>
                        </tr>
                        <tr>
                            <td><strong>Address</strong></td>
                            <td>' . $offer['Offer']['billing_address'] . '</td>
                        </tr>
                        <tr>
                            <td><strong>Country</strong></td>
                            <td>' . $offer['Country']['name'] . '</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>';
$pdf->writeHTMLCell(0, 0, 15, 60, $html, true, 1, 0, 0, 'L');


// CREATE THE INVOICE PRODUCTS DETAILS
$pdf->SetY(100);
$html = '<table cellpadding="0" cellspacing="0" style="border-bottom: 1px solid black;">
                <tr>
                    <td><strong>Product</strong></td>
                    <td><strong>Unit Price</strong></td>
                    <td><strong>Quantity</strong></td>
                    <td><strong>Discount (%)</strong></td>
                    <td><strong>Total</strong></td>
                </tr>
            </table>';
$subtotal = 0;
$vat = 0;
$html .= '<table cellpadding="0" cellspacing="0"><tbody>';
foreach ($offer['OffersDetail'] as $detail) {
    $subtotal = $subtotal + $detail['amount'];
    $html .= '<tr>';
    $html .= '<td>' . $detail['product_name'] . '</td>';
    $html .= '<td>' . $offer['Offer']['currency'] . ' ' . $detail['unit_price'] . '</td>';
    $html .= '<td>' . $detail['quantity'] . '</td>';
    $html .= '<td>' . $detail['discount'] . '</td>';
    $html .= '<td>' . $offer['Offer']['currency'] . ' ' . $detail['amount'] . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table><br/><br/>';
$pdf->writeHTML($html, true, false, false, false, 'C');

$html = '<table style="border-top: 1px solid black;font-size:bold;">
    <tr>
        <td>Total excl. VAT</td>
        <td>' . $offer['Offer']['currency'] . ' ' . $subtotal . '</td>
    </tr>
    <tr>
        <td>VAT amount</td>
        <td>';
if ($has_vat)
    $vat = ($subtotal * 0.21);
$html .= $offer['Offer']['currency'] . ' ' . $vat . '
                                </td>
                            </tr>
                            <tr>
                                <td>Total incl. VAT</td>
                                <td>' . $offer['Offer']['currency'] . ' ' . ($subtotal + $vat) . '</td>
                </tr>
        </table>';
$pdf->writeHTMLCell(0, 0, $pdf->getPageWidth() * 0.60, $pdf->GetY(), $html, false, 1, 0, 0, 'L');

if ($offer['Country']['uevat'] && !$has_vat) {
    $html = "<b>Services not submitted to VAT in Belgium according to art. 21, §7°, a) of VAT legal text</b>";
    $pdf->writeHTMLCell(0, 0, $pdf->GetX(), $pdf->GetY() + 20, $html, false, 1, 0, 0, 'L');
}

$pdf->Output('filename.pdf', 'I');
?>