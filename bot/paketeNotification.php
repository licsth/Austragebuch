<?php

include 'dbh.php';

$sql = "SELECT * FROM paket WHERE zeitpunkt > CURRENT_TIMESTAMP-86400";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $uid = $row['schueler_uid'];
    $sql2 = "SELECT telegram_id FROM schueler WHERE uid='$uid'";
    $result2 = mysqli_query($conn, $sql2);
    $telegram = mysqli_fetch_assoc($result2)['telegram_id'];
    if($telegram != null){
        $ort = $row['ort'];
        echo "$telegram Du hast ein neues Paket! Aufenthaltsort: <b>$ort</b><br>";
    }
    
}

?>