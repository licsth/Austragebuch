<?php
//Tests: Ist der Nutzer angemeldet und Schüler?
    session_start();
    if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
    } 
$uid = $_SESSION['uid'];
include 'dbh.php';
?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Zurücktragen</title>
</head>
<body role="document" class="form">
    <div class="container theme-showcase" role="main">
<br><br>
            <div class="row">
                <a href="schueler.php"><span class="glyphicon glyphicon-home" style="font-size: 2em;" aria-hidden="true"></span></a>
                <div class="col-lg-4 col-md-6 col-sm-8 center-block">
                    <h2>Zurücktragen</h2>
                    <!-- Formular zum Zurücktragen -->
                    <form method="post" action="processing/zuruecktragenp.php">
                        <p>Dein letzter Eintrag: </p>
                        <p><?php
                            //Letzten Eintrag wiedergeben zur Bestätigung
                        $sql = "SELECT * FROM eintrag WHERE uid='$uid' AND isback=0";
                    $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $wohin = $row['wohin'];
                            $back = $row['back'];
                            $date = DateTime::createFromFormat('Y-m-d H:i:s', $back);
                            $back = $date -> format("H:i, d.m.Y");
                            echo "Wohin? $wohin. Wann zurück? $back."
                            ?></p>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                    </form>
                </div>
                
            </div>
    </div>
</body>
</html>