<?php
    if(empty($_GET['str'])){
        echo "There was a problem: not all needed information given.";
    } else{
        $today = new DateTime();
        $tomorrow = $today -> modify('+1 day') -> format('D');
        $en = [$tomorrow, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $de = ['Morgen', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
    
        $str = $_GET['str'];
        $str = str_replace("%20", " ", $str);
        $str = str_replace($de, $en, $str);
        
        if($date = DateTime::createFromFormat('D', $str)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H:i*', $str)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H:i*', $str)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H:i', $str)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H.i*', $str)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H.i', $str)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H.i*', $str)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H.i', $str)){
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H*', $str)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D, H', $str)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H*', $str)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('D H', $str)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            while(new DateTime() > $date){
                $date = $date -> modify('+7 day');
            }
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H:i*', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H:i', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H.i*', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H.i', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m. H.i*', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m. H.i', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H.i*', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('d.m., H.i', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H:i*', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H:i', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H.i*', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H.i', $str)){
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H*', $str)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            $str = $date -> format('Y-m-d H:i:s');
        } else if($date = DateTime::createFromFormat('H', $str)){
            $hour = $date -> format("H");
            $date = $date -> setTime($hour, 0);
            $str = $date -> format('Y-m-d H:i:s');
        } else{
            echo "Date error";
            return;
        }

        echo $str;
        }
    
?>