<?php
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: logout.php");
} 
else {
    $role = $_SESSION['role'];
    if($role == 'sozpaed'){
        header("Location: logout.php");
    } 
    else{
        include 'dbh.php';

        $name = $_POST["name"];
        $name = str_replace("ä", "ae", $name);
        $name = str_replace("ö", "oe", $name);
        $name = str_replace("ü", "ue", $name);
        
        $wann = $_POST["wann"];
        $uid = $_SESSION['uid'];
        
        if(!$wann || !$uid){
            header('Location: austragen.php?err=empty');
        }

        $sql = "INSERT INTO gast(zeitraum, name, schueler_uid) VALUES ('$wann', '$name', '$uid')";
        $result = mysqli_query($conn, $sql);
        
        header("Location: schueler.php?src=gast");
    }
}
?>