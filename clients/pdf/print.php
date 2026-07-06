<?php

ob_start();

require('TCPDF-master/examples/tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tender Evaluation');
$pdf->SetTitle($access);
$pdf->SetSubject('Mabco Tender Evaluation');
$pdf->SetKeywords('Tender Evaluation');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, 0, "", $tender, array(10,64,255), array(10,64,140));

$pdf->setPrintHeader(false);
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 10, '', true);


$pdf->AddPage();

$html ='kkkkkkkkkkkkkkkkkkk';
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->setPrintHeader(true);


// Add a page
// This method has several options, check the source code documentation for more information.
//$pdf->AddPage();

//$pdf->Image('images/status.jpg', 160, 0, 60, '', '',  '', false, 300);











ob_end_clean();
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
//$pdf->Output('jjjjjjjjjj.pdf', 'I');
$pdf->Output(''.$access.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

?>