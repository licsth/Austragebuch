<?php

$date = DateTime::createFromFormat('H *', '13 Uhr');
echo $date -> format('d.m.Y H:i');

?>