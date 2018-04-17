<?php

session_start();
include 'dbh.php';
if(!isset($_SESSION['uid'])){
    header("Location: index.php");
}
if(empty($_GET['id'])){
    header("Location: logout.php");
}
else{
    
    $id = $_GET['id'];
    $sql = "UPDATE gast SET aktuell=0 WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    header("Location: gaeste.php");
    
}

?>