<?php
//Laden der $_SESSION-Variable (speichert Daten der Session, also uid, Passwort, Ausgetragen etc.)
    session_start();
//Connection für SQL
    include 'dbh.php';

//Prüfung, ob Nutzer eingeloggt ist
    if(!isset($_SESSION['uid'])){
        header("Location: index.php");
    }
//Prüfung, ob Nutzer ein Schüler ist
    if($_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
    }

    //Erneuerung von Daten, die extern geändert werden könnten
    $sql = "SELECT * FROM schueler WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    $_SESSION['postdienst'] = $row['postdienst'];
    $_SESSION['ausgetragen'] = $row['ausgetragen'];

//Speichern von Variablen, die später auf der Seite genutzt werden
    $ausgetragen = $_SESSION['ausgetragen'];
    $uid = $_SESSION['uid'];

?>
<html>
<head>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="stylesheet.css" rel="stylesheet">
    <!-- Bootstrap-Theme -->
    <title>Pakete</title>
</head>
<body role="document">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Menü -->
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
                if(!$ausgetragen){
                    echo "<a href='austragen.php'>
                        Austragen
                    </a>";
                } else{
                    echo "<a href='zurücktragen.php'>
                        Zurücktragen
                    </a>";
                }
                ?></li>
            <li><a href="gast.php">Gast anmelden</a></li>
            <li><a href="gaeste.php">Besuchsankündigungen</a></li>
              <li><a href="defekte.php">Mängel &amp; Defekte</a></li>
               <?php
                  if($_SESSION['postdienst']){
                      echo '<li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Postdienst <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="postdienst.php">Neues Paket</a></li>
                <li><a href="post_bearbeitung.php">Pakete bearbeiten</a></li>
              </ul>
            </li>';}
                  ?>
              <li class="active"><a href="#">Pakete</a></li>
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
        <h1>Pakete <?php
            //Anzahl Pakete in Klammern
            $sql = "SELECT COUNT(*) FROM paket WHERE schueler_uid='$uid' AND aktuell=1";
             $result = mysqli_query($conn, $sql);
             $row = mysqli_fetch_assoc($result);
             $anzahl = $row['COUNT(*)'];
            echo "($anzahl)";
            ?></h1><br>
        <?php

        //Auswahl aller aktuellen Pakete
         $sql = "SELECT id, ort, zeitpunkt FROM paket WHERE schueler_uid='$uid' AND aktuell=1 ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

         while($row = mysqli_fetch_assoc($result)){
            $ort = $row['ort'];
            $id = $row['id'];
            $zeitpunkt = $row['zeitpunkt'];
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $zeitpunkt);
            $zeitpunkt = $date -> format('d.m.Y');

             //ein Panel für jedes aktuelle Paket mit Link zum löschen
             echo "<div class='panel panel-info'>
             <div class='panel-heading'>
    <h3 class='panel-title'>Paket vom $zeitpunkt.<span aria-hidden='true'><a href='processing/pakete_aktuell.php?id=$id' class='close'>&times;</a></span></h3>
  </div><div class='panel-body'>";

             echo "Du hast ein Paket. <br>Ort: <strong>$ort</strong></div></div>";
         }

        ?>
    </div>
    <!-- Skripts: jQuery zum Ansteuern von Elementen auf der Seite und Bootstrap-Skript zum Beispiel für Dropdown-Menüs -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>
