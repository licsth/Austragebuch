<?php
session_start();

include 'dbh.php';

$uid = $_POST["uid"];
$pwd = $_POST["pwd"];

$uid = str_replace("'", "\'", $uid);
$pwd = str_replace("'", "\'", $pwd);

if(!$uid || !$pwd){
    header("Location: index.php?err=login");
} else{

$sql = "SELECT * FROM schueler WHERE uid='$uid'";
$result = mysqli_query($conn, $sql);

if(!$row = mysqli_fetch_assoc($result)){
    $sql = "SELECT * FROM sozpaed WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    if(!$row = mysqli_fetch_assoc($result)){
        header('Location: index.php?err=user');
    }
    else{
        if($row['pwd'] == $pwd){
            $_SESSION['uid'] = $row['uid'];
            $_SESSION['pwd'] = $row['pwd'];
            $_SESSION['role'] = 'sozpaed';
            $_SESSION['first'] = $row['first'];
            $_SESSION['last'] = $row['last'];
            header("Location: sozpaed.php");
        }
        else{
            header("Location: index.php?err=user");
        }
    }
}
else{
    
    if($row['pwd'] == $pwd){
        $_SESSION['uid'] = $row['uid'];
        $_SESSION['pwd'] = $row['pwd'];
        $_SESSION['role'] = 'schueler';
        $_SESSION['first'] = $row['first'];
        $_SESSION['last'] = $row['last'];
        header("Location: schueler.php");
    }
    else{
        header("Location: index.php?err=user");
    }
}
}
?>