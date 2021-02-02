<?php
include_once('../tcpdf.php');
include_once('./connection.php');
#geting necessary data from database
$choice = $_POST['choice'];
$con = new Connection();
$get_invoice_data = "SELECT * FROM invoices WHERE nr = $choice";
$invoice_data = mysqli_query($con -> getConnection() ,$get_invoice_data);
$invoice_data_array = mysqli_fetch_array($invoice_data);
$get_customer_data = "SELECT * FROM customers WHERE id = $invoice_data_array[1]";
$customer_data = mysqli_query($con -> getConnection() ,$get_customer_data);
$customer_data_array = mysqli_fetch_array($customer_data);
$get_rows_data = "SELECT * FROM `rows` WHERE invoice_nr = $invoice_data_array[0]";
$rows_data = mysqli_query($con -> getConnection() , $get_rows_data);
$con -> getConnection() -> close();
#setting up pdf
$invoice = new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
$invoice->SetCreator(PDF_CREATOR);
$invoice->SetAuthor(PDF_AUTHOR);
$invoice->SetTitle('Faktura'.$choice);
$invoice->SetFont('dejavusans', '', 9);
$invoice->AddPage();
#base data
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
        <td><b>Data wystawienia:</b> $invoice_data_array[4]</td>
    </tr>
    <tr>
        <td><b>Data sprzedaży:</b> $invoice_data_array[2]</td>
    </tr>
    <tr>
        <td><b>Termin płatności:</b> $invoice_data_array[3]</td>
    </tr>
    <tr>
        <td><b>Metoda płatności:</b> $invoice_data_array[5]</td>
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
        <td class=\"products\" style=\"width: 20px;\">L.p.</td>
        <td class=\"products\" style=\"width: 150px;\">Nazwa</td>
        <td class=\"products\" style=\"width: 25px;\">Jedn</td>
        <td class=\"products\" style=\"width: 55px;\">Ilość</td>
        <td class=\"products\">Cena netto</td>
        <td class=\"products\">Cena brutto</td>
        <td class=\"products\" style=\"width: 25px;\">VAT</td>
        <td class=\"products\" style=\"width: 70px;\">Wartość netto</td>
        <td class=\"products\" style=\"width: 70px;\">Wartość brutto</td>
    </tr>
";
#firts table with data from database
$zw = 0;
$zero = 0;
$five = 0;
$eight = 0;
$twenty_three = 0;
$summary_brutto = 0;
$i = 1;

while ($row = mysqli_fetch_row($rows_data)){
    $vat = $row[6];
    $price_brutto = (float)$row[5];
    $quantity = (int)$row[3];
    $price_netto = $vat != "zw" ?  (1.0 - $vat/100) * $price_brutto : $price_brutto;
    $value_brutto = $price_brutto * $quantity;
    $value_netto = $vat != "zw" ?  (1.0 - $vat/100) * $value_brutto : $value_brutto;
    $html .= "<tr><td class=\"products\">$i</td>";
    $html .= "<td class=\"products\">$row[2]</td>";
    $html .= "<td class=\"products\">$row[4]</td>";
    $html .= "<td class=\"products\">$row[3]</td>";
    $html .= "<td class=\"products\">$price_netto</td>";
    $html .= "<td class=\"products\">$price_brutto</td>";
    $html .= $vat != "zw" ? "<td class=\"products\">$vat%</td>" : "<td class=\"products\">$vat</td>";
    $html .= "<td class=\"products\">$value_netto</td>";
    $html .= "<td class=\"products\">$value_brutto</td>";
    $html .= "</tr>";
    switch($vat){
        case 'zw':
            $zw += $value_brutto;
            break;
        case '0':
            $zero += $value_brutto;
            break;
        case '5':
            $five += $value_brutto;
            break;
        case '8':
            $eight += $value_brutto;
            break;
        case '23':
            $twenty_three += $value_brutto;
            break;
    }
    $summary_brutto += $value_brutto;
    $i++;
}
$html .= "</table><div></div>";
#second table with summary
$payed = (float)$invoice_data_array[6];
$five_netto = round($five * 0.95,2);
$five_vat = $five - $five_netto;
$eight_netto = round($eight * 0.92,2);
$eight_vat = $eight - $eight_netto;
$twenty_three_netto = round($twenty_three * 0.77,2);
$twenty_three_vat = $twenty_three - $twenty_three_netto;
$summary_netto = $zw + $zero + $five_netto + $eight_netto + $twenty_three_netto;
$summary_vat = $summary_brutto - $summary_netto;
$summary = $summary_brutto - $payed;
$summary_word = num_to_words($summary);
$html .=
    "<table>
<tr>
<td>
    <table>
        <tr style=\"border: 1px solid black;\">
            <td class=\"products\" style=\"width: 32px;\"></td>
            <td class=\"products\" style=\"width: 25px;\">VAT</td>
            <td class=\"products\" style=\"width: 70px;\">Wartość netto</td>
            <td class=\"products\" style=\"width: 70px;\">Wartość VAT</td>
            <td class=\"products\" style=\"width: 70px;\">Wartość brutto</td>
        </tr>
        <tr>
            <td class=\"products\"></td>            
            <td class=\"products\">zw</td>
            <td class=\"products\">$zw</td>
            <td class=\"products\">0</td>
            <td class=\"products\">$zw</td>
        </tr>
        <tr>
            <td class=\"products\"></td>            
            <td class=\"products\">0%</td>
            <td class=\"products\">$zero</td>
            <td class=\"products\">0</td>
            <td class=\"products\">$zero</td>
        </tr>
        <tr>
            <td class=\"products\"></td>            
            <td class=\"products\">5%</td>
            <td class=\"products\">$five_netto</td>
            <td class=\"products\">$five_vat</td>
            <td class=\"products\">$five</td>
        </tr>
        <tr>
            <td class=\"products\"></td>            
            <td class=\"products\">8%</td>
            <td class=\"products\">$eight_netto</td>
            <td class=\"products\">$eight_vat</td>
            <td class=\"products\">$eight</td>
        </tr>
        <tr>
            <td class=\"products\"></td>            
            <td class=\"products\">23%</td>
            <td class=\"products\">$twenty_three_netto</td>
            <td class=\"products\">$twenty_three_vat</td>
            <td class=\"products\">$twenty_three</td>
        </tr>
        <tr>
            <td class=\"products\">Razem</td>            
            <td class=\"products\"></td>
            <td class=\"products\">$summary_netto</td>
            <td class=\"products\">$summary_vat</td>
            <td class=\"products\">$summary_brutto</td>
        </tr>
    </table>
    </td>
    <td style=\"width:110px\"></td>
    <td>
    <table>
        <tr>
            <td style=\"width:60px\">Zapłacono</td>
            <td style=\"width:180px;\">$invoice_data_array[6] PLN</td>
        </tr>
         <tr>
            <td style=\"width:60px\">Do zapłaty</td>
            <td style=\"width:180px\">$summary_brutto PLN</td>
        </tr>   
        <tr>
            <td style=\"width:60px\">Razem</td>
            <td style=\"width:180px; text-align: left;\">$summary PLN</td>
        </tr> 
        <tr>
            <td>Słownie</td>      
        </tr>
        <tr>
            <td style=\"width:240px\">$summary_word</td> 
        </tr>
    </table>
</td>
</tr>
    </table>
    <p><b>Uwagi</b><br>$invoice_data_array[7]</p>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <table>
    <tr>
        <td><p style=\"border-top: 1px solid black; text-align: center;\">imię i nazwisko osoby uprawnionej do wystwienia faktury</p></td>
        <td></td>
        <td></td>
        <td><p style=\"border-top: 1px solid black; text-align: center;\">imię i nazwisko osoby uprawnionej do odbioru faktury</p></td>
    </tr>
    </table>
    ";
#creating pdf
$invoice->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
ob_end_clean();
$invoice->Output('faktura'.$choice.'.pdf', 'I');

function num_to_words ($kw) {

    $t_a = array('','sto','dwieście','trzysta','czterysta','pięćset','sześćset','siedemset','osiemset','dziewięćset');
    $t_b = array('','dziesięć','dwadzieścia','trzydzieści','czterdzieści','pięćdziesiąt','sześćdziesiąt','siedemdziesiąt','osiemdziesiąt','dziewięćdziesiąt');
    $t_c = array('','jeden','dwa','trzy','cztery','pięć','sześć','siedem','osiem','dziewięć');
    $t_d = array('dziesięć','jedenaście','dwanaście','trzynaście','czternaście','piętnaście','szesnaście','siednaście','osiemnaście','dziewiętnaście');

    $t_kw_15 = array('septyliard','septyliardów','septyliardy');
    $t_kw_14 = array('septylion','septylionów','septyliony');
    $t_kw_13 = array('sekstyliard','sekstyliardów','sekstyliardy');
    $t_kw_12 = array('sekstylion','sekstylionów','sepstyliony');
    $t_kw_11 = array('kwintyliard','kwintyliardów','kwintyliardy');
    $t_kw_10 = array('kwintylion','kwintylionów','kwintyliony');
    $t_kw_9 = array('kwadryliard','kwadryliardów','kwaryliardy');
    $t_kw_8 = array('kwadrylion','kwadrylionów','kwadryliony');
    $t_kw_7 = array('tryliard','tryliardów','tryliardy');
    $t_kw_6 = array('trylion','trylionów','tryliony');
    $t_kw_5 = array('biliard','biliardów','biliardy');
    $t_kw_4 = array('bilion','bilionów','bilony');
    $t_kw_3 = array('miliard','miliardów','miliardy');
    $t_kw_2 = array('milion','milionów','miliony');
    $t_kw_1 = array('tysiąc','tysięcy','tysiące');
    $t_kw_0 = array('złoty','złotych','złote');

    if ($kw != '' or $kw != '0') {
        $kw = (substr_count($kw,'.') == 0) ? $kw.'.00' : $kw;
        $tmp = explode(".",$kw);
        $tmp[1] .= strlen($tmp[1]) == 1 ? '0' : '';
        $ln = strlen($tmp[0]);
        $l_pad = '';
        $tmp_a = ($ln%3 == 0) ? (floor($ln/3)*3):((floor($ln/3)+1)*3);
        for($i = $ln; $i < $tmp_a; $i++) {
            $l_pad .= '0';
            $kw_w = $l_pad . $tmp[0];
        }
        $kw_w = ($kw_w == '') ? $tmp[0] : $kw_w;
        $paczki = (strlen($kw_w)/3)-1;
        $p_tmp = $paczki;
        $kw_slow = '';
        for( $i = 0; $i <= $paczki; $i++) {
            $t_tmp = 't_kw_'.$p_tmp;
            $p_tmp--;
            $p_kw = substr($kw_w,($i*3),3);
            $kw_w_s = ($p_kw{1}!=1) ? $t_a[$p_kw{0}].' '.$t_b[$p_kw{1}].' '.$t_c[$p_kw{2}] : $t_a[$p_kw{0}].' '.$t_d[$p_kw{2}];
            if ( ($p_kw{0} == 0) && ($p_kw{2} == 1) && ($p_kw{1} < 1)){
                $ka = ${$t_tmp}[0];
            }
            else if ( ($p_kw{2} > 1 && $p_kw{2} < 5) && $p_kw{1} != 1){
                $ka = ${$t_tmp}[2];
            }
            else {
                $ka = ${$t_tmp}[1];
            }
            $kw_slow .= $kw_w_s.' '.$ka.' ';
        }
    }
    if ($kw == '0'){
        $kw_slow = 'zero złoty';
    }
    return $kw_slow.' '.(int)$tmp[1].'/100';
}
