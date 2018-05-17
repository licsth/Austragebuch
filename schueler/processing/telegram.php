<?php
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: ../index.php");
}

include 'dbh.php';
$uid = $_SESSION['uid'];
    if(!empty($_POST['telegramId'])){
        $id = $_POST['telegramId'];
        $sql = "UPDATE schueler SET telegram_id='$id' WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        header("Location: ../schueler.php?src=telegram");
    }
else{
    $sql = "UPDATE schueler SET telegram_id=NULL WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    header("Location: ../schueler.php?src=telegram");
}
        
    //}
?>