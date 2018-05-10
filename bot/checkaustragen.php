<?php
include 'dbh.php';
if(empty($_GET['id'])){
        echo "err: empty";
} else{
    $id = $_GET['id'];
    $sql = "SELECT * FROM schueler WHERE telegram_id='$id'";
    $result = mysqli_query($conn, $sql);
    if(!$row = mysqli_fetch_assoc($result)){
        echo "err: id";
    } else{
        if($row['ausgetragen']){
            echo "err: ausgetragen";
        }
        else{
            echo "success";
        }
    }
}
?>