<?php
session_start();
include 'dbh.php';
//Ist der Schüler angemeldet?
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "schueler"){
    header("Location: ../index.php");
    return;
}
//Wurde ein Ort angegeben?
if(empty($_POST['ort'])){
    header("Location: logout.php");
    return;
}
//Wurde eine ID angegeben?
if(empty($_GET['id'])){
    header("Location: logout.php");
    return;
}
//Ist der Nutzer vom Postdienst?
if(!$_SESSION['postdienst']){
        header("Location: logout.php");
    return;
    }
$ort =  mysqli_real_escape_string($conn, $_POST['ort']);
$id =  mysqli_real_escape_string($conn, $_GET['id']);
if (!is_numeric($id)) {
  header('Location: logout.php');
  return;
 }
if($ort == ""){
    header('Location: ../pakete_bearbeitung.php?err=ort');
}
else{
    //Informationen über das Paket aktualisieren
    $sql = "UPDATE paket SET ort='$ort' WHERE id=$id";
    mysqli_query($conn, $sql);
    header('Location: ../post_bearbeitung.php?src=paket');
}
?>
