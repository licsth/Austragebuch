<?php
include 'dbh.php';
    $sql = mysqli_query($conn, "SELECT uid FROM schueler");
	$array = array();
    while ($row = mysqli_fetch_array($sql)) {
        $array[] = $row['uid'];
    }
    //RETURN JSON ARRAY
    //echo json_encode($array);
// $array;

?>