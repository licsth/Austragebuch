<?php
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: logout.php");
} 
else {
    $role = $_SESSION['role'];
    if($role == 'admin' || $role == 'sozpaed'){
        header("Location: logout.php");
    } 
    else{
        include 'dbh.php';

        $wohin = $_POST["wohin"];
        $back = $_POST["back"];
        $absprache = $_POST["absprache"];
        $uid = $_SESSION['uid'];
        
        $sql = "SELECT * FROM schueler WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $wg = $row['wg'];
        
        if(!$wohin || !$back){
            header('Location: ../austragen.php?err=empty');
        }

        $sql = "INSERT INTO eintrag(uid, wg, back, absprache, wohin) VALUES ('$uid', '$wg', '$back', '$absprache', '$wohin')";
        $result = mysqli_query($conn, $sql);
        
        $sql = "UPDATE schueler SET ausgetragen=1 WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        
        header("Location: ../schueler.php?src=austragen");
    }
}
?>