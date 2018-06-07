<?php
session_start();
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "sozpaed"){
    header("Location: ../index.php");
    return;
}

include 'dbh.php';
$uid = $_SESSION['uid'];

$pwd = $_SESSION['pwd'];
//Stimmt das alte Passwort?
if(password_verify($_POST['old'], $pwd)){
    if(!empty($_POST['new'])){
        $new = $_POST['new'];
        $new = str_replace("'", "\'", $new);
        $new = password_hash($new, PASSWORD_BCRYPT);
        //Passwort aktualisieren
        $sql = "UPDATE sozpaed SET pwd='$new' WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $_SESSION['pwd'] = $new;
        header("Location: ../sozpaed.php?src=pwd");
    } else{
        header("Location: ../password.php?err=new");
    }
}
//Ansonsten: ist das alte Passwort noch provisorisches Passwort?
//TODO
else if($_POST['old'] == $pwd && $pwd == $uid){
    if(!empty($_POST['new'])){
        $new = $_POST['new'];
        $new = str_replace("'", "\'", $new);
        $new = password_hash($new, PASSWORD_BCRYPT);
        //Passwort aktualisieren
        $sql = "UPDATE sozpaed SET pwd='$new' WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $_SESSION['pwd'] = $new;
        header("Location: ../sozpaed.php?src=pwd");
    } else{
        header("Location: ../password.php?err=new");
    }
}
else{
    header("Location: ../password.php?err=pwd");
}
?>
