<?php
//Session beenden zum ausloggen
session_start();
session_destroy();
header("Location: index.php");
?>