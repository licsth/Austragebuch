<?php
    //Skript zum austragen einer Person über den Bot

    //Test: sind alle nötigen Informationen gegeben?
    if(empty($_GET['id']) || empty($_GET['back']) || empty($_GET['wohin'])){
        echo "There was a problem: not all needed information given.";
    } else{
        //Datum formatieren:
        //Falls "Morgen" als Tag angegeben wurde
        $today = new DateTime();
        $tomorrow = $today -> modify('+1 day') -> format('D');
        //Übersetzung deutsche zu englischen Wochentagen
        $en = [$tomorrow, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $de = ['Morgen', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
    
        //Informationen zum austragen auslesen
        $id = $_GET['id'];
        $back = $_GET['back'];
        //Leerzeichen rückersetzen
        $back = str_replace("%20", " ", $back);
        $back = str_replace($de, $en, $back);
        $wohin = $_GET['wohin'];
        $wohin = str_replace("%20", " ", $wohin);
        
        
        include 'dbh.php';
        
        //Test: Ist der Telegram-Nutzer im Austragebuch registriert?
        $sql = "SELECT * FROM schueler WHERE telegram_id='$id'";
        $result = mysqli_query($conn, $sql);
        if(!$row = mysqli_fetch_assoc($result)){
            echo "There was a problem: user not registered.";
            return;
        } else{
        
        $wg = $row['wg'];
        $uid = $row['uid'];
                
        if(!$wohin || !$back){
            echo('err: Not all information given');
        }
        
        //DateTime-Objekt aus angegebenem Zeitpunkt erstellen mit möglichen Datenformaten
        if($date = DateTime::createFromFormat('D', $back)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H:i*', $back)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H:i*', $back)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H:i', $back)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H.i*', $back)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H.i', $back)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H.i*', $back)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H.i', $back)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H*', $back)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H', $back)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H*', $back)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H', $back)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H:i*', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H:i', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H.i*', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H.i', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m. H.i*', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m. H.i', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H.i*', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H.i', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H:i*', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H:i', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H.i*', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H.i', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H*', $back)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H', $back)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            $back = $date -> format('Y-m-d H:i:s');
        } else{
            echo "Date error";
            return;
        }

        //Eintrag in die Datenbank einfügen
        $sql = "INSERT INTO eintrag(uid, back, absprache, wohin, isback) VALUES ('$uid', '$back', '', '$wohin', 0)";
        $result = mysqli_query($conn, $sql);
        
        //Schüler als ausgetragen markieren
        $sql = "UPDATE schueler SET ausgetragen=1 WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        
        //Rückmeldung für den Bot
        echo "success";
        }
    }
?>