<?php
include_once('./connection.php');
$choice = $_POST['choice'];
$con = new Connection();
$stmt = $con -> getConnection() -> prepare("DELETE FROM `invoices` WHERE nr = ?");
$stmt -> bind_param("i",$choice);
$stmt -> execute();
$stmt -> close();
$con -> getConnection() ->close();
header('Location: list.php');
exit();