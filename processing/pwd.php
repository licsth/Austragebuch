<?php
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: ../index.php");
}

include 'dbh.php';
$uid = $_SESSION['uid'];
$role = $_SESSION['role'];

$pwd = $_SESSION['pwd'];
if(password_verify($_POST['old'], $pwd)){
    if(!empty($_POST['new'])){
        $new = $_POST['new'];
        $new = str_replace("'", "\'", $new);
        $new = password_hash($new, PASSWORD_BCRYPT);
        $sql = "UPDATE $role SET pwd='$new' WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $_SESSION['pwd'] = $new;
        header("Location: ../$role.php?src=pwd");
    } else{
        header("Location: ../password.php?err=new");
    }
} else{
    header("Location: ../password.php?err=pwd");
}
?>