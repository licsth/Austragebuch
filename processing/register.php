<html><?php
    include 'dbh.php';
$type = $_FILES['file']['type'];
if($type != "text/csv"){
    header("Location: ../register.php?src=type");
    return;
}

$name = $_FILES['file']['tmp_name'];
$file = file($name)[0];
$file = str_replace(" ", "diesisteinleerzeichen", $file);
$entries = preg_split("/[\s,]+/", $file);

foreach($entries as $entry){
    $data = preg_split("/[\s;]+/", $entry);
    
    $wg = $data[2];
    $wg = str_replace("'", "\'", $wg);
    $wg = str_replace("diesisteinleerzeichen", " ", $wg);
    $first = $data[0];
    $first = str_replace("'", "\'", $first);
    $first = str_replace("diesisteinleerzeichen", " ", $first);
    $last = $data[1];
    $last = str_replace("'", "\'", $last);
    $last = str_replace("diesisteinleerzeichen", " ", $last);

    $uid = strtolower($first) . "." . strtolower($last);
    $pwd = password_hash($uid, PASSWORD_BCRYPT);

    $sql = "SELECT * FROM schueler WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);

    if($row = mysqli_fetch_assoc($result)){
        //header("Location: schuelerregister.php?err=uid");
    } else{

    $sql = "INSERT INTO schueler(uid, pwd, first, last, wg) VALUES ('$uid', '$pwd', '$first', '$last', '$wg');";
    //$result = mysqli_query($conn, $sql);
        echo $sql;
    }
    
}
    ?></html>