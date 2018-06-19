<?php
session_start();
//Ist der Schüler angemeldet?
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
        //Eintrag als zurückgetragen markieren
        $sql = "UPDATE eintrag SET isback=1 WHERE uid='$uid' AND isback=0 OR isback IS NULL";
        $result = mysqli_query($conn, $sql);
        //Schüler als nicht ausgetragen vermerken
        $sql = "UPDATE schueler SET ausgetragen=0 WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $_SESSION['ausgetragen'] = false;

        header("Location: ../schueler.php?src=zurücktragen");
    }
}
?>
