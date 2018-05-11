<?php
session_start();

if(!isset($_SESSION['uid'])){
    header("Location: logout.php");
} else{
    
    include 'dbh.php';

    $wg = $_POST["wg"];
    $first = $_POST["first"];
    $last = $_POST["last"];
    
    if(!$wg || !$first || !$last ){
        header('Location: schuelerregister.php?err=register');
    }
    
    $uid = strtolower($first) . "." . strtolower($last);
    $pwd = password_hash($uid, PASSWORD_BCRYPT);
    
    $sql = "SELECT * FROM schueler WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);

    if($row = mysqli_fetch_assoc($result)){
        header("Location: schuelerregister.php?err=uid");
    } else{

    $sql = "INSERT INTO schueler(uid, pwd, first, last, wg) VALUES ('$uid', '$pwd', '$first', '$last', '$wg')";
    $result = mysqli_query($conn, $sql);
        
    header("Location: schuelerregister.php");
}}
?>