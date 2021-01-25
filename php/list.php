<html lang="pl">
    <head>
        <meta charset = utf-8>
        <link rel="stylesheet" href="../css/list.css">
        <title>Lista Faktur</title>
    </head>
    <body>
        <h1>Lista faktur</h1>
    </body>
</html>
<?php
$con = mysqli_connect('localhost', 'root', '','iai');
$get_all_invoices = "SELECT nr, company_name, date_of_issue FROM invoices INNER JOIN customers ON invoices.customer_id = customers.id ORDER BY date_of_issue DESC";
$all_invoices = mysqli_query($con, $get_all_invoices);

echo "<form target='_blank' method='POST' action='pdf.php'><table border='1'>";
while ($row = mysqli_fetch_row($all_invoices)){
    echo "<tr>";
    foreach ($row as $value){
        echo "<td>$value</td>";
    }
    echo "<td><button name='choice' value='$row[0]' type='submit'>download pdf</button></td></tr>";
}
echo "</form>";