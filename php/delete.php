<?php
$choice = $_POST['choice'];
$con = mysqli_connect('localhost','root','','iai');
$stmt = $con -> prepare("DELETE FROM `invoices` WHERE nr = ?");
$stmt -> bind_param("i",$choice);
$stmt -> execute();
$stmt -> close();
$con -> close();
header('Location: list.php');
exit();