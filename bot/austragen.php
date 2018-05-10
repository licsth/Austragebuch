<?php
    if(empty($_GET['id']) || empty($_GET['back']) || empty($_GET['wohin'])){
        echo "There was a problem: not all needed information given.";
    } else{
        $en = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $deshort = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
        $delong = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
        $today = new DateTime();
        $tomorrow = $today -> modify('+1 day') -> format('D');
        
        $id = $_GET['id'];
        $back = $_GET['back'];
        $back = str_replace("%20", " ", $back);
        //$back = str_replace("Morgen", $tomorrow, $back);
        $back = str_replace($deshort, $en, $back);
        $back = str_replace($delong, $en, $back);
        $wohin = $_GET['wohin'];
        $wohin = str_replace("%20", " ", $wohin);
        
        
        include 'dbh.php';
        
        $sql = "SELECT * FROM schueler WHERE telegram_id='$id'";
        $result = mysqli_query($conn, $sql);
        if(!$row = mysqli_fetch_assoc($result)){
            echo "There was a problem: user not registered.";
        } else{
        
        $wg = $row['wg'];
        $uid = $row['uid'];
                
        if(!$wohin || !$back){
            header('Location: ../austragen.php?err=empty');
        }
        
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
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H*', $back)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H', $back)){
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
            $back = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H', $back)){
            $back = $date -> format('Y-m-d H:i:s');
        } else{
            echo "Date error";
            return;
        }

        $sql = "INSERT INTO eintrag(uid, back, absprache, wohin, isback) VALUES ('$uid', '$back', '', '$wohin', 0)";
        $result = mysqli_query($conn, $sql);
        
        $sql = "UPDATE schueler SET ausgetragen=1 WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        
        echo "success";
        }
    }
?>