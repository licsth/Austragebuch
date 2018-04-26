<?php
session_start();
include 'dbh.php';

if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'schueler'){
    header("Location: logout.php");
}    

$name = $_POST['schueler'];
$name = str_replace("'", "\'", $name);
$names = preg_split("/[\s,]+/", $name);

$first = $names[0];
$last = $names[1];

if(empty($_POST['ort'])){
    $ort = $_SESSION['wg'];
} else{
    $ort = $_POST['ort'];
    $ort = str_replace("'", "\'", $ort);
}

$sql = "SELECT * FROM schueler WHERE first='$first' AND last='$last'";
$result = mysqli_query($conn, $sql);

if(!$row = mysqli_fetch_assoc($result)){
    header("Location: ../postdienst.php?name=$uid&src=name");
} else{
    $uid = $row['uid'];
    $sql = "INSERT INTO paket(schueler_uid, ort) VALUES ('$uid', '$ort')";
    $result = mysqli_query($conn, $sql);
    header("Location: ../postdienst.php?src=paket");
}
?>