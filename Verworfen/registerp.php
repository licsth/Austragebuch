<?php
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: logout.php");
} 
else {
    else{
        include 'dbh.php';

        $first = $_POST["first"];
        $last = $_POST["last"];
        $role = $_POST["role"];
        $uid = strtolower($first) . "." . strtolower($last);
        if($role == 'sozpaed'){
            $uid = substr(strtolower($first), 0, 1) . "." . strtolower($last);
        }

        if(!$first || !$last){
            header('Location: register.php?err=register');
        }

        $sql = "SELECT * FROM user WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);

        if($row = mysqli_fetch_assoc($result)){
            header("Location: register.php?err=uid");
        } else{

            $sql = "INSERT INTO user(uid, pwd, first, last, role) VALUES ('$uid', '$uid', '$first', '$last', '$role')";
            $result = mysqli_query($conn, $sql);

            if($role == 'schueler'){
                $_SESSION['schueleruid'] = $uid;
                header("Location: schuelerregister.php");
            }
            else {
                header("Location: register.php");
            }
        }
    }
}
?>
