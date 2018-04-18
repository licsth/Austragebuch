<?php

session_start();

$role = $_SESSION['role'];
if(!isset($_SESSION['uid']) || $role == 'admin' || $role == 'schueler' || !isset($_GET['anzahl'])){
    header("Location: logout.php");
} 

else{
    $anzahl = $_GET['anzahl'];
    header("Location: ../austragebuch.php?anzahl=$anzahl");
}


?>