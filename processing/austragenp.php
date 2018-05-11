<?php
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: logout.php");
} 
else {
    $role = $_SESSION['role'];
    if($role == 'admin' || $role == 'sozpaed'){
        header("Location: logout.php");
    } 
    else{
        include 'dbh.php';

        $wohin = $_POST["wohin"];
        $back = $_POST["back"];
        $today = new DateTime();
        $tomorrow = $today -> modify('+1 day') -> format('D');
        $tomorrow = str_replace($en, $deshort, $tomorrow);
        $en = [$tomorrow, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $de = ['Morgen', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
        $back = str_replace($de, $en, $back);
        
        $absprache = $_POST["absprache"];
        $uid = $_SESSION['uid'];
        
        $sql = "SELECT * FROM schueler WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $wg = $row['wg'];
        
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
        }  else if($date = DateTime::createFromFormat('D, H.i*', $back)){
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
            header("Location: ../austragen.php?err=date&wohin=$wohin&back=$back&absprache=$absprache");
            return;
        }

        $sql = "INSERT INTO eintrag(uid, back, absprache, wohin, isback) VALUES ('$uid', '$back', '$absprache', '$wohin', 0)";
        $result = mysqli_query($conn, $sql);
        
        $sql = "UPDATE schueler SET ausgetragen=1 WHERE uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $_SESSION['ausgetragen'] = true;
        
        header("Location: ../schueler.php?src=austragen");
    }
}
?>