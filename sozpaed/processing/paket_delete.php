<?php
session_start();
include 'dbh.php';
if(!isset($_SESSION['uid'])){
    header("Location: ../index.php");
}
if(empty($_GET['ort'])){
    header("Location: logout.php");
}
if(!$_SESSION['postdienst']){
        header("Location: logout.php");
    }
$id =  $_GET['id'];
$sql = "DELETE FROM paket WHERE id=$id";
mysqli_query($conn, $sql);
header('Location: ../post_bearbeitung.php?src=del');
?>