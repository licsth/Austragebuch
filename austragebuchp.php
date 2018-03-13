<?php
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: logout.php");
} 
else {
    $role = $_SESSION['role'];
    if($role == 'admin' || $role == 'schueler'){
        header("Location: logout.php");
    } 
    else{
        if(empty($_POST['bis'])){
            $_POST['bis'] = 'CURDATE() + 1';
        }
        if(empty($_POST['von'])){
            $_POST['von'] = '';
        }
        $_SESSION['von'] = $_POST['von'];
        $_SESSION['bis'] = $_POST['bis'];
        header("Location: austragebuch.php");
    }
}
?>