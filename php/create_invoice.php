<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Faktura dodana pomyślnie</title>
    </head>

</html>
<?php
echo "<pre><h1>Wysłano</h1></pre>";

$con = mysqli_connect('localhost','root','','iai');
$kw = 'SELECT * FROM customers';
$res = mysqli_query($con,$kw);
printTable($res);
$con -> close();

function printTable($result){
    echo "<table border=1>";
    while($p = mysqli_fetch_row($result)){
        echo "<tr>";
        foreach($p as $value){
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
}
