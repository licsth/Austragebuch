<?php
include 'dbh.php';
if(empty($_GET['id'])){
        echo "err: empty";
} else{
    //Sofern die jeweilige Telegram-ID einem Schüler zugeordnet ist, zurückgeben, ob dieser Postdienst ist
    $id = $_GET['id'];
    $sql = "SELECT * FROM schueler WHERE telegram_id='$id'";
    $result = mysqli_query($conn, $sql);
    if(!$row = mysqli_fetch_assoc($result)){
        echo "err: id";
    } else{
        if($row['postdienst']){
            echo "true";
        }
        else{
            echo "false";
        }
    }
}
?>