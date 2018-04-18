<?php
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: logout.php");
} 
else {
    $role = $_SESSION['role'];
    if($role != 'schueler'){
        header("Location: logout.php");
    } 
    else{
        include 'dbh.php';
        $uid = $_SESSION['uid'];
        $sql = "UPDATE eintrag SET isback=1 WHERE uid='$uid' AND isback=0";
        $result = mysqli_query($conn, $sql);
        
        $sql = "UPDATE schueler SET ausgetragen=0 WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        
        header("Location: ../schueler.php?src=zurücktragen");
    }
}
?>