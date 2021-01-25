<?php
$con = mysqli_connect('localhost', 'root', '','iai');
$get_all_invoices = "SELECT nr, company_name, date_of_issue FROM invoices INNER JOIN customers ON invoices.customer_id = customers.id";
$all_invoices = mysqli_query($con, $get_all_invoices);

echo "<table border='1'>";
while ($row = mysqli_fetch_row($all_invoices)){
    echo "<tr>";
    foreach ($row as $value){
        echo "<td>$value</td>";
    }
    echo "<td><button>download pdf</button></td></tr>";
}
