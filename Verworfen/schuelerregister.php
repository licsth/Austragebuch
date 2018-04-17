<?php
    session_start();
    
include 'dbh.php';
?>
<html>
<head>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css">
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
    <title>Schüler registrieren</title>
</head>
<body role="document">
    <?php
        $err = '';
        if(!empty($_GET['err'])){
            $err = $_GET["err"];
        }
    ?>
    <div class="container theme-showcase" role="main">
            <div class="row">
                <div class="middle">
                    <h2>Schüler hinzufügen</h2>
                    <?php
                        if($err == 'register'){
                            echo "<div class='alert alert-danger' role='alert'>Bitte gültige Werte eingeben.</div>";
                        } else if($err == "uid"){
                            echo "<div class='alert alert-danger' role='alert'>Benutzername bereits vergeben.</div>";
                        }
                    ?>
                    <form method="post" action="schuelerregisterp.php">
                        <input type="text" class="form-control" placeholder="Vorname" name="first">
                        <input type="text" class="form-control" placeholder="Nachname" name="last">
                        <input type="text" class="form-control" placeholder="WG" name="wg"><br>
                        <br>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                    </form>
                </div>
                
            </div>
    </div>
</body>
</html>