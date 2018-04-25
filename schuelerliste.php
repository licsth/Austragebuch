<?php
include 'dbh.php';
    $sql = mysqli_query($conn, "SELECT * FROM schueler");
	$array = array();
    while ($row = mysqli_fetch_array($sql)) {
        $array[] = $row['first'] . " " . $row['last'];
    }
    //RETURN JSON ARRAY
    echo json_encode($array);
// $array;

?>