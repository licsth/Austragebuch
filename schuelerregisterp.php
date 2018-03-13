<?php
session_start();

if(!isset($_SESSION['uid'])){
    header("Location: logout.php");
} else if($_SESSION['role'] == 'schueler' || $_SESSION['role'] == 'sozpaed'){
    header("Location: logout.php");
} else{
session_start();
include 'dbh.php';

$wg = $_POST["wg"];
$soz = $_POST["soz"];
$uid = $_SESSION['schueleruid'];

if(!$wg){
    header('Location: schuelerregister.php?err=register');
}

$sql = "INSERT INTO schueler(uid, wg, ausgetragen, sozpaed) VALUES ('$uid', '$wg', 'false', '$soz')";
$result = mysqli_query($conn, $sql);

    $_SESSION['schueleruid'] = null;
header("Location: register.php");
}
?>