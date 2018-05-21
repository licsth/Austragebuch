<?php
//Skript, um Pakete als nicht mehr aktuelle  zu markieren
session_start();
include 'dbh.php';
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "schueler"){
    header("Location: ../index.php");
}
if(empty($_GET['id'])){
    header("Location: logout.php");
}
else{
    //SQL-Anweisungen
    $id = $_GET['id'];
    $sql = "UPDATE gast SET aktuell=0 WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    header("Location: ../gaeste.php");
    
}

?>