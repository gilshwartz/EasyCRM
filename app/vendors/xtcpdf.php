<?php

App::import('Vendor', 'tcpdf/tcpdf');

class XTCPDF extends TCPDF {

    var $xheadertext = 'PDF created using CakePHP and TCPDF';
    var $xheadercolor = array(0, 0, 200);
    var $xfootertext = 'Copyright Â© %d XXXXXXXXXXX. All rights reserved.';
    var $xfooterfont = PDF_FONT_NAME_MAIN;
    var $xfooterfontsize = 8;

    /**
     * Overwrites the default footer
     * set the text in the view using
     * $fpdf->xfootertext = 'Copyright Â© %d YOUR ORGANIZATION. All rights reserved.';
     */
    function Footer() {
        $year = date('Y');
        $footertext = sprintf($this->xfootertext, $year);
        $this->SetY(-20);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont($this->xfooterfont, '', $this->xfooterfontsize);
        $this->Cell(150, 8, $footertext, 'T', 0, 'L');

        if (empty($this->pagegroups)) {
            $pagenumtxt = $this->l['w_page'] . ' ' . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages();
        } else {
            $pagenumtxt = $this->l['w_page'] . ' ' . $this->getPageNumGroupAlias() . ' / ' . $this->getPageGroupAlias();
        }
        //Print page number
        $this->SetX($this->original_lMargin);
        $this->Cell(0, 8, $pagenumtxt, 'T', 0, 'R');
    }

}

?>