<?php
//Ist der Schüler angemeldet? Wurde eine ID angegeben?
session_start();
include 'dbh.php';
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "schueler"){
    header("Location: ../index.php");
}
if(empty($_GET['id'])){
    header("Location: logout.php");
}
else{
    //Paket als nicht aktuell markieren
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    if (!is_numeric($id)) {
     	header('Location: logout.php');
      return;
     }
    $sql = "UPDATE paket SET aktuell=0 WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    header("Location: ../pakete.php");

}

?>
