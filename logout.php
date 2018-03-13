<?php
session_start();
session_destroy();
setcookie('eingeloggt', false);
header("Location: index.php");
?>