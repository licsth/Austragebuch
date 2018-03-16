<?php
include 'dbh.php';
$sql = 'SELECT first, last, uid FROM user WHERE role="schueler"';
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)){
    $first = $row['first'];
    $uid = $row['uid'];
    $last = $row['last'];
    $sql = "UPDATE schueler SET first='$first' WHERE uid='$uid'; UPDATE schueler SET last='$last' WHERE uid='$uid';";
    echo "<p>$sql</p>";
    $result2 = mysqli_query($conn, $sql);
}
?>