<?php
include 'dbh.php';
if(empty($_GET['id'])){
    header("Location: logout.php");
}
$id = $_GET['id'];
$sql = "UPDATE gast SET bestaetigt=1 WHERE id=$id";
$result = mysqli_query($conn, $sql);
header("Location: besuch.php?src=besuch");

?>