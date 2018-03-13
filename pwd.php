<?php
if(!isset($_SESSION['uid'])){
    header("Location: index.php");
}

session_start();
include 'dbh.php';
$uid = $_SESSION['uid'];
$role = $_SESSION['role'];
$page = $role;
if($page == 'student'){
    $page = "schueler";
} else if($page == 'soz'){
    $page = 'sozpaed';
}
$pwd = $_SESSION['pwd'];

if($pwd == $_POST['old']){
    if(!empty($_POST['new'])){
        $new = $_POST['new'];
        $sql = "UPDATE user SET pwd='$new' WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $_SESSION['pwd'] = $new;
        header("Location: $page.php?src=pwd");
    } else{
        header("Location: password.php?err=new");
    }
} else{
    header("Location: password.php?err=pwd");
}
?>