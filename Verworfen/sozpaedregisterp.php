<?php
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: logout.php");
} 
else {
    
    include 'dbh.php';

    $first = $_POST["first"];
    $last = $_POST["last"];
    
    if(!$first || !$last){
        header('Location: sozpaedregister.php?err=register');
    }
    
    $uid = substr(strtolower($first), 0, 1) . "." . strtolower($last);

    $sql = "SELECT * FROM sozpaed WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);

    if($row = mysqli_fetch_assoc($result)){
        header("Location: sozpaedregister.php?err=uid");
    } else{

        $sql = "INSERT INTO sozpaed(uid, pwd, first, last) VALUES ('$uid', '$uid', '$first', '$last')";
        $result = mysqli_query($conn, $sql);
        
        header("Location: sozpaedregister.php");
    }
    
}
?>