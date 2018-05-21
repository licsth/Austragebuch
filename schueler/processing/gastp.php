<?php
session_start();
//Ist der Nutzer angemeldet und ein Schüler?
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "schueler"){
    header("Location: logout.php");
} 
else {
    include 'dbh.php';

    //Daten über den Besuch auslesen
    $name = $_POST["name"];
    $name = str_replace("ä", "ae", $name);
    $name = str_replace("ö", "oe", $name);
    $name = str_replace("ü", "ue", $name);

    $wann = $_POST["wann"];
    $uid = $_SESSION['uid'];

    if(!$wann || !$uid){
        header('Location: ../austragen.php?err=empty');
    }

    //Besuch in der Datenbank vermerken
    $sql = "INSERT INTO gast(zeitraum, name, schueler_uid) VALUES ('$wann', '$name', '$uid')";
    $result = mysqli_query($conn, $sql);

    header("Location: ../schueler.php?src=gast");
    
}
?>