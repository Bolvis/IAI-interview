<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/create_invoice.css">
        <title>Faktura dodana pomyślnie</title>
    </head>
    <body>
<?php
#connect to database
$con = mysqli_connect('localhost','root','','iai');
#if doesn't exist add customer to database
$nip = $_POST['nip'];
$stmt = $con -> prepare("SELECT * FROM customers WHERE nip = ?");
$stmt -> bind_param("i",$nip);
$stmt -> execute();
$res = $stmt -> get_result();
$stmt -> close();

if (mysqli_num_rows($res) != 1){
    if(!isset($_POST['is_in'])){
        $buyer = $_POST['buyer'];
        $zip = $_POST['zip'];
        $town = $_POST['town'];
        $street = $_POST['street'];
        $apartment = $_POST['apartment'];
        $stmt = $con -> prepare("INSERT INTO `customers`(`nip`, `company_name`, `zip`, `street`, `apartment`, `town`) VALUES(?,?,?,?,?,?)");
        $stmt -> bind_param("isssss", $nip, $buyer, $zip, $street, $apartment, $town);
        $stmt -> execute();
        $stmt -> close();
    }
}
#adding invoice
$stmt = $con -> prepare("SELECT id FROM customers WHERE nip = ?");
$stmt -> bind_param("i",$nip);
$stmt -> execute();
$id = $stmt -> get_result();
$id_value = getValue($id);

$sale_date = $_POST['sale_date'];
$date_of_issue = $_POST['date_of_issue'];
$payment_date = $_POST['payment_date'];
$payment_method = $_POST['payment_method'];
$payed = $_POST['payed'];
$comments = $_POST['comments'];
$stmt = $con -> prepare("INSERT INTO `invoices`(`customer_id`, `sale_date`, `payment_date`, `date_of_issue`, `payment_method`, `payed`, `comments`) VALUES (?,?,?,?,?,?,?)");
$stmt -> bind_param("sssssss", $id_value, $sale_date, $payment_date, $date_of_issue, $payment_method, $payed, $comments);
$stmt -> execute();
$stmt -> close();
#get invoice number
$stmt = $con -> prepare("SELECT MAX(nr) FROM invoices"); #zdaję sobie sprawę z niepoprawności tego rozwiązanie jednak na tem moment tylko takie przychodzi mi do głowy
$stmt -> execute();
$invoice_number = $stmt -> get_result();
$invoice_number_value = getValue($invoice_number);
#add products
if (isset($_POST['name_of_item'])){
    $name_of_item = $_POST['name_of_item'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $price_brutto = $_POST['price_brutto'];
    $vat = $_POST['vat'];
    for ($i = 0; $i < count($name_of_item); $i++){
        $stmt = $con -> prepare("INSERT INTO `rows`(`invoice_nr`, `name`, `quantity`, `unit`, `price_brutto`, `vat`) VALUES (?,?,?,?,?,?)");
        $stmt -> bind_param("isdsds",$invoice_number_value, $name_of_item[$i], $quantity[$i], $unit[$i], $price_brutto[$i], $vat[$i]);
        $stmt -> execute();
        $stmt -> close();
    }
}
#dissconect from database
$con -> close();
#success screen
echo "<p>Faktura dodana pomyślnie</p><a href='../index.html'>Powrót do strony głównej</a>";
#function for getting value of exact element from database
function getValue($result){
    $p = mysqli_fetch_row($result);
    return $p[0];
}
