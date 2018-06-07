<?php
//Tests: ist der Nutzer angemeldet? Ist der Nutzer ein SozPäd?
session_start();
if(!isset($_SESSION['uid']) || $_SESSION['role'] != "sozpaed"){
    header("Location: index.php");
}
include 'dbh.php';
$uid = $_SESSION['uid'];
$src = '';
if(!empty($_GET['src'])){
$src = $_GET['src'];
}
$name = '';
if(!empty($_GET['name'])){
$name = $_GET['name'];
}
?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="style.css" rel="stylesheet">
    <title>Passwort zurücksetzen</title>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        //Methode, um Schülernamen per Autocomplete vorzuschlagen
    $( function() {
      var availableTags = <?php include 'schuelerliste.php'; ?>;
      $( "#tags" ).autocomplete({
        source: availableTags
      });
    } );
    </script>
</head>
<body role="document" class="form">

    <div class="container theme-showcase" role="main">
        <br>
        <div class="row">
            <a href="sozpaed.php"><span class="glyphicon glyphicon-home" style="font-size: 2em;" aria-hidden="true"></span></a>
            <div class="col-lg-5 col-md-8 col-sm-10 center-block">
                <h2>Schülerpasswort zurücksetzen</h2>
                <p>
                  Hier kannst du Schülerpasswörter zurücksetzen. Gib dazu einfach den Nutzernamen oder den vollständigen Namen des Schülers an. Das neue Passwort wird identisch zum Nutzernamen sein.
                </p>
                <?php
                        //Fehlermeldungen
                    if($src == 'name'){
                        echo "<div class='alert alert-danger' role='alert'>Der angegebene Schüler wurde nicht gefunden.</div>";
                    }
                ?>
                <!-- Formular, um Pakete hinzuzufügen -->
                <form class="form" action="processing/schueler_passwort.php" method="post">
                    <div class="form-group">
                        <input name="schueler" type="text" id="tags" class="form-control
                                                                            <?php
                                                                            //Roter Rahmen um das Feld, falls vorher ungültige Angaben gemacht wurden
                                                                            if($src=='name'){
                                                                                echo " bg-danger";
                                                                            }
                                                                            ?>" <?php
                                                                            if($name!=''){
                                                                                echo "value='$name'";
                                                                            }
                                                                            ?>>

                    </div>
                    <button type="submit" class="btn btn-default">Okay</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
