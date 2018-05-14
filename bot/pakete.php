<?php
include 'dbh.php';
if(empty($_GET['id'])){
        echo "err: empty";
} else{
    $id = $_GET['id'];
    $sql = "SELECT * FROM schueler WHERE telegram_id='$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(!$row){
        echo "Anscheinend ist dein Telegram-Account nicht im Austragebuch registriert.\n"
                		. "Um den Austragebuch-Bot nutzen zu können, navigiere im digitalen Austragebuch zu dem Menüpunkt [dein Nutzername]>Telegram und gib dort folgende Nummer ein: \n<b>$id</b>";
        return;
    }
    $uid = $row['uid'];
    
    $sql = "SELECT COUNT(*) FROM paket WHERE schueler_uid='$uid' AND aktuell=1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $anzahl = $row['COUNT(*)'];
    
    if($anzahl != 1) echo "Du hast $anzahl neue Pakete!";
    else echo "Du hast ein neues Paket!";
    
    $id = $_GET['id'];
    $sql = "SELECT id, ort, zeitpunkt FROM paket WHERE schueler_uid='$uid' AND aktuell=1 ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

     while($row = mysqli_fetch_assoc($result)){
        $ort = $row['ort'];
        $id = $row['id'];
        $zeitpunkt = $row['zeitpunkt'];
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $zeitpunkt);
        $zeitpunkt = $date -> format('d.m.Y');
         echo "\nEin Paket vom <b>$zeitpunkt</b> mit Aufenthaltsort <b>$ort</b> /$id";
}
}
?>