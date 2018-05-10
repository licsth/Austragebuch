<?php
include 'dbh.php';
if(empty($_GET['id'])){
        echo "err: empty";
} else{
    $id = $_GET['id'];
    $sql = "SELECT * FROM schueler WHERE telegram_id='$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $uid = $row['uid'];
    
    $sql = "UPDATE eintrag SET isback=1 WHERE uid='$uid' AND isback=0";
    $result = mysqli_query($conn, $sql);
    if(!$result){
        echo "err";
        return;
    }
    
    $sql = "UPDATE schueler SET ausgetragen=0 WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    if(!$result){
        echo "err";
        return;
    }
    echo "success";
}
?>