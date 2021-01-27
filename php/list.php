<html lang="pl">
    <head>
        <meta charset = utf-8>
        <link rel="stylesheet" href="../css/list.css">
        <title>Lista Faktur</title>
    </head>
    <body>
        <a href="../index.html">Strona Główna</a>
        <h1>Lista faktur</h1>
        <form target='_blank' method='POST' action='pdf.php' id="download"></form>
        <form method='POST' action='delete.php' id="delete"></form>
    </body>
</html>
<?php
#getting database data
$con = mysqli_connect('localhost', 'root', '','iai');
$get_all_invoices = "SELECT nr, company_name, date_of_issue FROM invoices INNER JOIN customers ON invoices.customer_id = customers.id ORDER BY date_of_issue DESC";
$all_invoices = mysqli_query($con, $get_all_invoices);
#creating list
echo "<table border='1'>";
while ($row = mysqli_fetch_row($all_invoices)){
    echo "<tr>";
    foreach ($row as $value){
        echo "<td>$value</td>";
    }
    echo "<td><button name='choice' value='$row[0]' form='download' type='submit'>pobierz pdf</button></td>
          <td><button name='choice' value='$row[0]' form='delete' type='submit'>usuń fakturę</button></td>
           </tr>";
}
$con -> close();