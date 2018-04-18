<?php
session_start();
if(!isset($_SESSION['uid'])){
    echo "Hi";
    //header("Location: ../index.php");
}

include 'dbh.php';
$uid = $_SESSION['uid'];
$role = $_SESSION['role'];

$pwd = $_SESSION['pwd'];
if($pwd == $_POST['old']){
    if(!empty($_POST['new'])){
        $new = $_POST['new'];
        $new = str_replace("'", "\'", $new);
        $sql = "UPDATE $role SET pwd='$new' WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $_SESSION['pwd'] = $new;
        echo "Fertig.";
        header("Location: ../$role.php?src=pwd");
    } else{
        echo "Kein Passwort.";
        header("Location: ../password.php?err=new");
    }
} else{
    echo "Falsches Passwort.";
    header("Location: ../password.php?err=pwd");
}
?>