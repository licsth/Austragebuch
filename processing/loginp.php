<html><?php
session_start();

include 'dbh.php';

//Angegebene Nutzerdaten auslesen
$uid = $_POST["uid"];
$pwd = $_POST["pwd"];


if(!$uid || !$pwd){
    //Falls nicht alle Daten angegeben wurden
    header("Location: ../index.php?err=login");
} else{

    //Apostrophe escapen, um SQL-Injection zu vermeiden
$uid = str_replace("'", "\'", $uid);
$pwd = str_replace("'", "\'", $pwd);

//Schülerdaten aus der Datenbank auslesen
$sql = "SELECT * FROM schueler WHERE uid='$uid'";
$result = mysqli_query($conn, $sql);

if(!$row = mysqli_fetch_assoc($result)){
    //Wenn kein passender Schüler gefunden wurde: testen, ob es ein SozPäd sein könnte
    $sql = "SELECT * FROM sozpaed WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    if(!$row = mysqli_fetch_assoc($result)){
        header('Location: ../index.php?err=user');
    }
    else{
        //Tests: stimmt das Passwort mit dem gehashten Passwort überein?
        if(password_verify($pwd, $row['pwd'])){
            $_SESSION['uid'] = $row['uid'];
            $_SESSION['pwd'] = $row['pwd'];
            $_SESSION['role'] = 'sozpaed';
            $_SESSION['first'] = $row['first'];
            $_SESSION['last'] = $row['last'];
            header("Location: ../sozpaed/sozpaed.php");
        }
        else{
            //Ansonsten: Anmeldedaten sind nicht korrekt
            header("Location: ../index.php?err=user");
        }
    }
}
else{
    //Tests: stimmt das Passwort mit dem gehashten Passwort überein?
    if(password_verify($pwd, $row['pwd'])){
        $_SESSION['uid'] = $row['uid'];
        $_SESSION['pwd'] = $row['pwd'];
        $_SESSION['role'] = 'schueler';
        $_SESSION['first'] = $row['first'];
        $_SESSION['last'] = $row['last'];
        $_SESSION['wg'] = $row['wg'];
        $_SESSION['postdienst'] = $row['postdienst'];
        $_SESSION['ausgetragen'] = $row['ausgetragen'];
        header("Location: ../schueler/schueler.php");
    }
    else{
        //Ansonsten: Anmeldedaten sind nicht korrekt
        header("Location: ../index.php?err=user");
    }
}
}
    ?></html>
