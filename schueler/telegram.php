<?php
//Tests: Ist der Nutzer angemeldet und ein Schüler?
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: index.php");
}
include 'dbh.php';
$uid = $_SESSION['uid'];

 if($_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
 }
 //Erneuerung von Daten, die extern geändert werden könnten
 $sql = "SELECT * FROM schueler WHERE uid='$uid'";
 $result = mysqli_query($conn, $sql);
 $row = mysqli_fetch_assoc($result);
 $_SESSION['postdienst'] = $row['postdienst'];
 $_SESSION['ausgetragen'] = $row['ausgetragen'];
$first = $_SESSION['first'];
$last = $_SESSION['last'];

//Fehlermeldungen
$err = '';
if(!empty($_GET['err'])){
    $err = $_GET['err'];
}

//Telegram-ID als Platzhalter
$sql = "SELECT telegram_id FROM schueler WHERE uid='$uid'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$id = $row['telegram_id'];

?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    </head>
    <body class="form">

    <div class="container theme-showcase" role="main">
        <br>
        <div class="row">
            <a href="schueler.php"><span class="glyphicon glyphicon-home" style="font-size: 2em;" aria-hidden="true"></span></a>
            <div class="col-lg-4 col-md-6 col-sm-8 center-block">
    <h2>Telegram</h2>
                <p>
                    <?php
                    echo "Du bist eingeloggt als $first $last ($uid)."
                    ?>
                </p>
                <!-- Formular, um die assoziierte Telegram-ID hinzuzufügen, zu ändern oder zu löschen -->
                <form method="post" action="processing/telegram.php">
                    <div class="form-group">
                    <input type="text" class="form-control" placeholder="Telegram-ID" name="telegramId" <?php

                        if($id != "" && $id != null){
                            echo "value='$id'";
                        }

                        ?>></div>
                    <p>Bitte gib hier deine Telegram-ID an.</p>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
