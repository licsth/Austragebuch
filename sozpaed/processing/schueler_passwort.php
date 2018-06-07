<?php
session_start();
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "sozpaed"){
    header("Location: ../index.php");
    return;
}

include 'dbh.php';
$uid = $_SESSION['uid'];

//Angegebenen Namen speichern
if(empty($_POST['schueler'])){
  header("Location: ../schueler_passwort.php?src=name");
  return;
}
$name = $_POST['schueler'];
$name = str_replace("'", "\'", $name);
$names = preg_split("/[\s,]+/", $name);

$first = $names[0];
$last = $names[1];

$sql = "SELECT uid FROM schueler WHERE first='$first' AND last='$last' OR uid='$name' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(!$row){
  header("Location: ../schueler_passwort.php?src=name&name=$name");
} else{
  $uid = $row['uid'];
  $pwd = password_hash($uid, PASSWORD_BCRYPT);
  $sql = "UPDATE schueler SET pwd='$pwd' WHERE uid='$uid'";
  $result = mysqli_query($conn, $sql);
  header("Location: ../sozpaed.php?src=name&name=$name");
}

?>
