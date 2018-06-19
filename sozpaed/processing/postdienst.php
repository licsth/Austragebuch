<?php
session_start();
//Überprüfen der Zugriffsrechte
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "sozpaed"){
    header("Location: ../index.php");
    return;
}

include 'dbh.php';
$uid = $_SESSION['uid'];

//Angegebenen Namen speichern
if(empty($_POST['schueler'])){
  header("Location: ../postdienst.php?src=name");
  return;
}
$name = mysqli_real_escape_string($conn, $_POST['schueler']);
$names = preg_split("/[\s,]+/", $name);

$first = $names[0];
$last = $names[1];

//Schüler in der Datenbank suchen
$sql = "SELECT uid FROM schueler WHERE first='$first' AND last='$last' OR uid='$name' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(!$row){
  //Falls kein Schüler gefunden wurde
  header("Location: ../postdienst.php?src=name&name=$name");
} else{
  //Schülerdaten verändern: Postdienst festlegen
  $uid = $row['uid'];
  $pwd = password_hash($uid, PASSWORD_BCRYPT);
  $sql = "UPDATE schueler SET postdienst=1 WHERE uid='$uid'";
  $result = mysqli_query($conn, $sql);
  header("Location: ../sozpaed.php?src=postdienst&name=$name");
}

?>
