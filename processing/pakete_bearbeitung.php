<?php
session_start();
include 'dbh.php';
if(!isset($_SESSION['uid'])){
    header("Location: ../index.php");
}
if(empty($_POST['ort'])){
    header("Location: logout.php");
}
if(empty($_GET['id'])){
    header("Location: logout.php");
}
if(!$_SESSION['postdienst']){
        header("Location: logout.php");
    }
$ort =  $_POST['ort'];
$id =  $_GET['id'];
if($ort == ""){
    header('Location: ../pakete_bearbeitung.php?err=ort');
}
else{
    $sql = "UPDATE paket SET ort='$ort' WHERE id=$id";
    mysqli_query($conn, $sql);
    header('Location: ../post_bearbeitung.php?src=paket');
}
?>