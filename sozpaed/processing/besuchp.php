<?php
//Skript zum Bestätigen von Besuchsankündigungen
session_start();
include 'dbh.php';

if(empty($_GET['id']) || !isset($_SESSION['uid']) || $_SESSION['role'] != "sozpaed"){
    header("Location: logout.php");
    return;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
if (!is_numeric($id)) {
 	header('Location: logout.php');
 }
//Besuchsnkündigung bestätigen
$sql = "UPDATE gast SET bestaetigt=1 WHERE id=$id";
$result = mysqli_query($conn, $sql);
//Weiterleitung zu Besuchsankündigungen
header("Location: ../besuch.php?src=besuch");

?>
