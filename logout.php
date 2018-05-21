<?php
//Logout-Funktion: Session wird beendet
session_start();
session_destroy();
//Weiterleitung zur Startseite
header("Location: index.php");
?>