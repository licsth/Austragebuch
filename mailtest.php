<?php
$m = mail('linda.thelen@arcor.de', 'Test', 'Dis is a test.');
if($m == true){
    echo 'Worked?';
}
?>