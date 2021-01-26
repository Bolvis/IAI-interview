<?php
include_once('../tcpdf.php');
$choice = $_POST['choice'];
$con = mysqli_connect('localhost','root','','iai');
$get_invoice_data = "SELECT * from invoices WHERE nr = $choice";
$invoice_data = mysqli_query($con,$get_invoice_data);
$invoice_data_array = mysqli_fetch_array($invoice_data);
$get_customer_data = "SELECT * from customers WHERE id = $invoice_data_array[1]";
$customer_data = mysqli_query($con,$get_customer_data);
$customer_data_array = mysqli_fetch_array($customer_data);
$con->close();
$invoice = new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
$invoice->SetCreator(PDF_CREATOR);
$invoice->SetAuthor(PDF_AUTHOR);
$invoice->SetTitle('Faktura'.$choice);
$invoice->SetFont('dejavusans', '', 12);
$invoice->AddPage();
$html = <<<EOD
<style> 
/*TODO zrobić ładne style*/
</style>
<img src="../images/logo.png" alt="logo" width="250">
<div>Faktura nr $choice</div>
<div>Data wystawienia: $invoice_data_array[4]</div>
<div>Data sprzedaży: $invoice_data_array[2]</div>
<div>Termin płatności: $invoice_data_array[3]</div>
<div>Metoda płatności: $invoice_data_array[5]</div>
<p>Sprzedawca
    <div>Fajowa firma Sp. z o. o.</div>
    <div>Ulica 7B/12</div>
    <div>00-000 Szczecin</div>
    <div>NIP: 5614719189</div>
    <div>numer konta<br>11 2222 3333 4444 5555 6666 7777</div>
</p>
<p>Nabywca
    <div>$customer_data_array[2]</div>
    <div>$customer_data_array[4] $customer_data_array[5]</div>
    <div>$customer_data_array[3] $customer_data_array[6]</div>
    <div>NIP: $customer_data_array[1]</div>
</p>
EOD;
$invoice->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$invoice->Output('faktura'.$choice.'.pdf', 'I');