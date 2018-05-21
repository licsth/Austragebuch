<?php
//Session beenden zum Ausloggen
session_start();
session_destroy();
header("Location: ../index.php");
?>