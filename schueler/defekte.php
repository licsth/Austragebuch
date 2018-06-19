<?php
    session_start();
    include 'dbh.php';
//Test, ob der Nutzer angemeldet und ein Schüler ist
    if(!isset($_SESSION['uid'])){
        header("Location: index.php");
    }
    if($_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
    }

    //Erneuerung von Daten, die extern geändert werden könnten
    $sql = "SELECT * FROM schueler WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    $_SESSION['postdienst'] = $row['postdienst'];
    $_SESSION['ausgetragen'] = $row['ausgetragen'];
    
//Fehlermeldungen
    $err = '';
    if(!empty($_GET['err'])){
        $err = $_GET['err'];
    }
    $uid = $_SESSION['uid'];
    $ausgetragen = $_SESSION['ausgetragen'];
?>
<html>
<head>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Mängel und Defekte</title>
</head>
<body role="document">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Titel und Schalter werden für eine bessere mobile Ansicht zusammengefasst -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Navigation ein-/ausblenden</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="schueler.php">Austragebuch</a>
        </div>

        <!-- Alle Navigationslinks, Formulare und anderer Inhalt werden hier zusammengefasst und können dann ein- und ausgeblendet werden -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="schueler.php">Start <span class="sr-only">(aktuell)</span></a></li>
            <li><?php
                //individueller Menüpunkt Austragen/Zurücktragen
                if(!$ausgetragen){
                    echo "<a href='austragen.php'>
                        Austragen
                    </a>";
                } else{
                    echo "<a href='zuruecktragen.php'>
                        Zurücktragen
                    </a>";
                }
                ?></li>
            <li><a href="gast.php">Gast anmelden</a></li>
            <li><a href="gaeste.php">Besuchsankündigungen</a></li>
            <li class="active"><a href="#">Mängel &amp; Defekte</a></li>
               <?php
              //individueller Menüpunkt Postdienst
                  if($_SESSION['postdienst']){
                      echo '<li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Postdienst <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="postdienst.php">Neues Paket</a></li>
                <li><a href="post_bearbeitung.php">Pakete bearbeiten</a></li>
              </ul>
            </li>';}
                  ?>
              <li><a href="pakete.php">Pakete<?php
                //Menüpunkt Pakete mit Anzahl neuer Pakete
                $sql = "SELECT COUNT(*) FROM paket WHERE aktuell=1 AND schueler_uid='$uid'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $count = $row['COUNT(*)'];
                if($count > 0){
                    echo " <span class='badge'>$count</span>";
                }

                ?></a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $uid; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="password.php">Passwort ändern</a></li>
                <li><a href="telegram.php">Telegram</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="logout.php">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    <div class="container theme-showcase" role="main">
        <h1>Mängel und Defekte</h1><br><br>
        <!-- Formular für Mängel und Defekte -->
        <form class="form center-block col-md-8" action="processing/defektep.php" method="post">
            <div class="form-group">
                <label for="mangel" class="control-label">*Mangel / Beschreibung</label>
                <textarea rows="3" name="mangel" type="text" class="form-control
                                                                    <?php
                                                                    if($err=='mo' || $err=='mangel'){
                                                                        echo " bg-danger";
                                                                    }
                                                                    ?>"></textarea>
            </div>
            <div class="form-group">
                <label for="ort" class="control-label">*Genauer Ort</label>
                <input name="ort" type="text" class="form-control
                                                                    <?php
                                                                    if($err=='mo' || $err=='ort'){
                                                                        echo " bg-danger";
                                                                    }
                                                                    ?>">
            </div>
            <div class="form-group">
                <label for="bemerkung" class="control-label">Bemerkung zur Schadensentstehung</label>
                <input name="bemerkung" type="text" class="form-control">
            </div>
            <button type="submit" class="btn btn-default">Abschicken</button>
        </form>
    </div>
    <!-- JQuery und Bootstrap-Skript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>
