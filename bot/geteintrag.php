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
    
    $sql = "SELECT * FROM eintrag WHERE uid='$uid' AND isback=0";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $wohin = $row['wohin'];
    $back = $row['back'];
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $back);
    $uhrzeit = $date -> format("H:i");
    $datum = $date -> format("d.m.Y");
    echo "Wohin? <b>$wohin</b>. Wann zurück? <b>$uhrzeit Uhr</b>, $datum.";
}
?>