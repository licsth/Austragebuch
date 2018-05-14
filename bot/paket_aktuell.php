 <?php
include 'dbh.php';
$telegram_id = $_GET['schueler_id'];

$paket_id = $_GET['paket_id'];

if(empty($_GET['schueler_id']) || empty($_GET['paket_id'])){
    header("Location: ../logout.php");
    return;
}

$sql = "SELECT * FROM schueler WHERE telegram_id='$telegram_id'";
    $result = mysqli_query($conn, $sql);
    if(!$row = mysqli_fetch_assoc($result)){
        echo "err: id";
        return;
    }
$uid = $row['uid'];
        
$sql = "SELECT * FROM paket WHERE id=$paket_id";
    $result = mysqli_query($conn, $sql);
    if(!$row = mysqli_fetch_assoc($result)){
        echo "err: paket";
        return;
    }
$schueler_uid = $row['schueler_uid'];

if($uid == $schueler_uid){
    $sql ="UPDATE paket SET aktuell=0 WHERE id=$paket_id";
    $result = mysqli_query($conn, $sql);
    echo "success";
    return;
}
else{
    echo "err: uid";
    }
?>