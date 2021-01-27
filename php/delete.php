<?php
$choice = $_POST['choice'];
$con = mysqli_connect('localhost','root','','iai');
$delete_rows = "DELETE FROM `rows` WHERE invoice_nr = $choice";
$delete_invoice = "DELETE FROM `invoices` WHERE nr = $choice";
mysqli_query($con,$delete_rows);
mysqli_query($con,$delete_invoice);
$con -> close();
header('Location: list.php');
exit();