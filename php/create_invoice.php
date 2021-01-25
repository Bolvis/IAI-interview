<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Faktura dodana pomyślnie</title>
    </head>

</html>
<?php
#connect to database
$con = mysqli_connect('localhost','root','','iai');
#if doesn't exist add customer to database
$nip = $_POST['nip'];
$check_if_exists = "SELECT * FROM customers WHERE nip = $nip";
$res = mysqli_query($con,$check_if_exists);

if (mysqli_num_rows($res) != 1){
    $buyer = $_POST['buyer'];
    $zip = $_POST['zip'];
    $town = $_POST['town'];
    $street = $_POST['street'];
    $apartment = $_POST['apartment'];
    $add_customer = "INSERT INTO `customers`(`nip`, `company_name`, `zip`, `street`, `apartment`, `town`) VALUES 
                    ('$nip', '$buyer', '$zip', '$street', '$apartment', '$town')";
    mysqli_query($con,$add_customer);
}
#adding invoice
$customer_id = "SELECT id FROM customers WHERE nip = $nip";
$id = mysqli_query($con,$customer_id);
$invoice_customer_id = getValue($id);
$sale_date = $_POST['sale_date'];
$date_of_issue = $_POST['date_of_issue'];
$payment_date = $_POST['payment_date'];
$create_invoice = "INSERT INTO `invoices`(`customer_id`, `sale_date`, `payment_date`, `date_of_issue`) VALUES
                    ('$invoice_customer_id','$sale_date','$payment_date','$date_of_issue')";
mysqli_query($con,$create_invoice);
#dissconect from database
$con -> close();
#test function for printing query results
function printDatabase($result){
    echo "<table border=1>";
    while($p = mysqli_fetch_row($result)){
        echo "<tr>";
        foreach($p as $value){
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
}
#function for getting value of id for invoice
function getValue($result){
    $p = mysqli_fetch_row($result);
    return $p[0];
}

