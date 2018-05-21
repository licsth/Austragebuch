<?php
//Verbindung zur Datenbank
$conn = mysqli_connect("localhost", "root", "", "Austragebuch");

if(!$conn){
    die("Connetion failed: " . mysqli_connect_error());
}

?>