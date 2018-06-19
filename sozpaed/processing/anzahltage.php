<?php
//Skript zum Einstellen der Anzahl letzter Tage beim Austragebuch
session_start();

//Tests: Ist der Nutzer angemeldet? Ist der Nutzer ein SozPäd? Wurde eine Anzahl an Tagen angegeben?
$role = $_SESSION['role'];
if(!isset($_SESSION['uid']) || $role == 'admin' || $role == 'schueler' || !isset($_GET['anzahl'])){
    header("Location: logout.php");
}

else{
    //Weiterleitung zum Austragebuch
    $anzahl = mysqli_real_escape_string($conn, $_GET['anzahl']);
    header("Location: ../austragebuch.php?anzahl=$anzahl");
}


?>
