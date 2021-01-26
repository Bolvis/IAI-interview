<?php
include_once('../tcpdf.php');
#geting necessary data from database
$choice = $_POST['choice'];
$con = mysqli_connect('localhost','root','','iai');
$get_invoice_data = "SELECT * FROM invoices WHERE nr = $choice";
$invoice_data = mysqli_query($con,$get_invoice_data);
$invoice_data_array = mysqli_fetch_array($invoice_data);
$get_customer_data = "SELECT * FROM customers WHERE id = $invoice_data_array[1]";
$customer_data = mysqli_query($con,$get_customer_data);
$customer_data_array = mysqli_fetch_array($customer_data);
$get_rows_data = "SELECT * FROM `rows` WHERE invoice_nr = $invoice_data_array[0]";
$rows_data = mysqli_query($con, $get_rows_data);
$con->close();
#setting up pdf
$invoice = new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
$invoice->SetCreator(PDF_CREATOR);
$invoice->SetAuthor(PDF_AUTHOR);
$invoice->SetTitle('Faktura'.$choice);
$invoice->SetFont('dejavusans', '', 9);
$invoice->AddPage();
$html = "
<style>
.products{
    border: 1px solid black;
    text-align: center;
} 
</style>
<img src=\"../images/logo.png\" alt=\"logo\" width=\"250\">
<table>
    <tr>
        <td style=\"text-align: center; font-size:14px;\">Faktura nr $choice<br></td>
    </tr>
    <tr>
        <td><p>Data wystawienia: $invoice_data_array[4] &nbsp; Data sprzedaży: $invoice_data_array[2]</p></td>
    </tr>
    <tr>
        <td><p>Termin płatności: $invoice_data_array[3] &nbsp;&nbsp;&nbsp; Metoda płatności: $invoice_data_array[5]</p></td>
    </tr>
</table>
<br>
<table>
    <tr>
        <td>
            <p><b>Sprzedawca</b><br>
Fajowa firma Sp. z o. o.<br>
Ulica 7B/12<br>
00-000 Szczecin<br>
NIP: 5614719189<br>
numer konta<br>
11 2222 3333 4444 5555 6666 7777</p>
        </td>
        <td>
            <p style=\"text-align: right;\">
            <b>Nabywca</b><br>
                $customer_data_array[2]<br>
                $customer_data_array[4] $customer_data_array[5]<br>
                $customer_data_array[3] $customer_data_array[6]<br>
                NIP: $customer_data_array[1]
            </p>
        </td>
    </tr>
</table>
<br>
<div></div>
<table>
    <tr style=\"border: 1px solid black;\">
        <td class=\"products\" style=\"width: 20px;\">L.P</td>
        <td class=\"products\" style=\"width: 185px;\">Nazwa</td>
        <td class=\"products\" style=\"width: 25px;\">Jend</td>
        <td class=\"products\" style=\"width: 55px;\">Ilość</td>
        <td class=\"products\">Cena brutto</td>
        <td class=\"products\" style=\"width: 35px;\">Stawka</td>
        <td class=\"products\">Wartość netto</td>
        <td class=\"products\" style=\"width: 80px;\">Wartość brutto</td>
    </tr>
";
$i = 1;
while ($row = mysqli_fetch_row($rows_data)){
    $html .= "<tr><td class=\"products\">$i</td>";
    $html .= "<td class=\"products\">$row[2]</td>";
    $html .= "<td class=\"products\">$row[4]</td>";
    $html .= "<td class=\"products\">$row[3]</td>";
    $html .= "<td class=\"products\">$row[5]</td>";
    $row[7] != "zw" ? $html .= "<td class=\"products\">$row[7]%</td>" : $html .= "<td class=\"products\">$row[7]</td>";
    $html .= "<td class=\"products\"></td>";
    $html .= "<td class=\"products\"></td>";
    $html .= "</tr>";
    $i++;
}
$html .= "</table>";
#creating pdf
$invoice->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$invoice->Output('faktura'.$choice.'.pdf', 'I');