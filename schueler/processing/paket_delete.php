<?php
session_start();
include 'dbh.php';
//Wurde eine ID angegeben?
//Ist der Nutzer...
//...angemeldet?
//...ein Schüler?
//...vom Postdienst?
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "schueler"){
    header("Location: ../index.php");
    return;
}
if(empty($_GET['id'])){
    header("Location: logout.php");
    return;
}
if(!$_SESSION['postdienst']){
        header("Location: logout.php");
    return;
    }
$id =  mysqli_real_escape_string($conn, $_GET['id']);
if (!is_numeric($id)) {
  header('Location: logout.php');
  return;
 }
//Paket löschen
$sql = "DELETE FROM paket WHERE id=$id";
mysqli_query($conn, $sql);
header('Location: ../post_bearbeitung.php?src=del');
?>
