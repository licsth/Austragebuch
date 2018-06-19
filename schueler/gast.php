<?php
    session_start();
    if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
    }
    //Erneuerung von Daten, die extern geändert werden könnten
    $sql = "SELECT * FROM schueler WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    $_SESSION['postdienst'] = $row['postdienst'];
    $_SESSION['ausgetragen'] = $row['ausgetragen'];
    
?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Gast anmelden</title>
</head>
<body role="document" class="form">
    <?php
        $err = '';
        if(!empty($_GET['err'])){
            $err = $_GET["err"];
        }
    ?>
    <div class="container theme-showcase" role="main">
<br><br>
            <div class="row">
                <a href="schueler.php"><span class="glyphicon glyphicon-home" style="font-size: 2em;" aria-hidden="true"></span></a>
                <div class="col-lg-4 col-md-6 col-sm-8 center-block">
                    <h2>Gast anmelden</h2>
                    <?php
                    //eventuelle Fehlermeldung
                        if($err == 'empty'){
                            echo "<div class='alert alert-danger' role='alert'>Bitte gültige Werte eingeben.</div>";
                        }
                    ?>
                    <!-- Formular, um Gäste anzumelden -->
                    <form method="post" action="processing/gastp.php">
                        <input type="text" class="form-control" placeholder="Zeitraum" name="wann">
                        <input type='text' class="form-control" placeholder="Name des Gastes" name="name"><br>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                    </form>
                </div>

            </div>
    </div>
</body>
</html>
