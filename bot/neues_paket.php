<?php
    if(empty($_GET['schueler_uid']) || empty($_GET['ort'])){
        echo "There was a problem: not all needed information given.";
    } else{
        
        include 'dbh.php';
        
        $schueler_uid = $_GET['schueler_uid'];
        $schueler_uid = str_replace("%20", " ", $schueler_uid);
        $ort = $_GET['ort'];
        
        $sql = "SELECT * FROM schueler WHERE uid ='$schueler_uid'";
        $result = mysqli_query($conn, $sql);
        if(!$row = mysqli_fetch_assoc($result)){
            $names = preg_split("/[\s,]+/", $schueler_uid);

            $first = $names[0];
            $last = $names[1];
            
            $sql = "SELECT * FROM schueler WHERE first='$first' AND last='$last'";
            $result = mysqli_query($conn, $sql);

            if(!$row = mysqli_fetch_assoc($result)){
                echo "Es wurde unter diesem Namen leider kein Schüler gefunden.";

            }
        }        
        
        $uid = $row['uid'];
        $sql = "INSERT INTO paket(schueler_uid, ort) VALUES ('$uid', '$ort')";
        $result = mysqli_query($conn, $sql);
        if($result){
            
        echo "Das Paket wurde erfolgreich registriert";
        } else{
            echo "Das Paket konnte nicht registriert werden...";
        }
            
    }
        
?>