<?php
session_start();
include 'dbh.php';
//Ist der Schüler angemeldet?
if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'schueler'){
    header("Location: logout.php");
}    

//Name auslesen, SQL-Injection vermeiden
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

//Schülerdaten anhand von Vor- und Nachname auslesen
$sql = "SELECT * FROM schueler WHERE first='$first' AND last='$last'";
$result = mysqli_query($conn, $sql);

if(!$row = mysqli_fetch_assoc($result)){
    //Falls kein zugehöriger Schüler gefunden wurde
    header("Location: ../postdienst.php?name=$uid&src=name");
} else{
    $uid = $row['uid'];
    //Paket in die Datenbank einfügen
    $sql = "INSERT INTO paket(schueler_uid, ort) VALUES ('$uid', '$ort')";
    $result = mysqli_query($conn, $sql);
    if($result){
        header("Location: ../postdienst.php?src=paket");
    }
    else{
        header("Location: ../postdienst.php?src=paketerr");
    }
}
?>