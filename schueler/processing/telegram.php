<?php
session_start();
//Ist der Schüler angemeldet?
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "schueler"){
    header("Location: ../index.php");
}

include 'dbh.php';
$uid = $_SESSION['uid'];
    if(!empty($_POST['telegramId'])){
        $id = $_POST['telegramId'];
        //Neue Telegram-ID in die Datenbank einfügen
        $sql = "UPDATE schueler SET telegram_id='$id' WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        header("Location: ../schueler.php?src=telegram");
    }
else{
    //Telegram-ID aus der Datenabnk löschen
    $sql = "UPDATE schueler SET telegram_id=NULL WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    header("Location: ../schueler.php?src=telegram");
}
        
?>