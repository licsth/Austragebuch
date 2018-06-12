<html><?php

    //CSV-Import von Schülern
    include 'dbh.php';
    //.csv-Datei prüfen
$type = $_FILES['file']['type'];
if($type != "text/csv"){
    header("Location: ../register.php?src=type");
    return;
}

    $role = $_POST['role'];

    //Datei speichern
$name = $_FILES['file']['tmp_name'];
$file = file($name);
foreach($file as $entry){
    //Für jeden Schüler in der Datei:
    $data = preg_split("/[;]+/", $entry);
    //Leerzeichen rückersetzen, Apostrophe escapen
    if($role == "schueler"){
        $wg = $data[2];
        $wg = str_replace("'", "\'", $wg);
        $wg = str_replace("diesisteinleerzeichen", " ", $wg);
    }
    $first = $data[0];
    $first = str_replace("'", "\'", $first);
    $first = str_replace("diesisteinleerzeichen", " ", $first);
    $last = $data[1];
    $last = str_replace("'", "\'", $last);
    $last = str_replace("diesisteinleerzeichen", " ", $last);

    //uid nach dem Schema vorname.nachname erstellen
    if($role == "schueler"){
        $uid = strtolower($first) . "." . strtolower($last);
    } else{
        $uid = substr(strtolower($first), 0, 1) . "." . strtolower($last);
    }
    $pwd = password_hash($uid, PASSWORD_BCRYPT);

    //Ist der Nutzername schon vergeben?
    $sql = "SELECT * FROM $role WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);

    if($row = mysqli_fetch_assoc($result)){
        //header("Location: schuelerregister.php?err=uid");
    } else{
        if($role == "schueler"){
        //Schüler in die Datenbank einfügen
            $sql = "INSERT INTO schueler(uid, pwd, first, last, wg) VALUES ('$uid', '$pwd', '$first', '$last', '$wg');";
            $result = mysqli_query($conn, $sql);
        } else{
            $sql = "INSERT INTO sozpaed(uid, pwd, first, last) VALUES ('$uid', '$pwd', '$first', '$last');";
            $result = mysqli_query($conn, $sql);
        }

    }

}
    header('Location: ../register.php');
    ?></html>
