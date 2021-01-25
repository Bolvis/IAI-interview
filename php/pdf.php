<?php
include_once('../tcpdf.php');
$choice = $_POST['choice'];
$invoice = new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);

$invoice->Output('faktura'.$choice.'.pdf', 'I');