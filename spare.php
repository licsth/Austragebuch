<?php

$today = new DateTime();
echo $today -> modify('+1 day') -> format('D');

?>