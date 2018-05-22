<?php
//Skript zum Bestätigen von Besuchsankündigungen
session_start();
include 'dbh.php';

if(empty($_GET['id']) || !isset($_SESSION['uid']) || $_SESSION['role'] != "sozpaed"){
    header("Location: logout.php");
    return;
} 

$id = $_GET['id'];
//Besuchsnkündigung bestätigen
$sql = "UPDATE gast SET bestaetigt=1 WHERE id=$id";
$result = mysqli_query($conn, $sql);
//Weiterleitung zu Besuchsankündigungen
header("Location: ../besuch.php?src=besuch");

?>