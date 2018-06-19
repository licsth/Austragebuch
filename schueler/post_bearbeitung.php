<?php
//Prüfungen:
//Ist der Nutzer angemeldet?
//Ist der Nutzer ein Schüler?
//Ist der Schüler vom Postdienst?
    session_start();
    include 'dbh.php';

    if(!isset($_SESSION['uid'])){
        header("Location: index.php");
    }
    if($_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
    }
    $uid = $_SESSION['uid'];
    //Erneuerung von Daten, die extern geändert werden könnten
    $sql = "SELECT * FROM schueler WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $_SESSION['postdienst'] = $row['postdienst'];
    $_SESSION['ausgetragen'] = $row['ausgetragen'];
    if(!$_SESSION['postdienst']){
        header("Location: logout.php");
    }
    $ausgetragen = $_SESSION['ausgetragen'];
    $uid = $_SESSION['uid'];

    $src = '';
if(!empty($_GET['src'])){
    $src = $_GET['src'];
}

?>
<html>
<head>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Postdienst</title>
    <link href="style.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

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
              <li><a href="defekte.php">Mängel &amp; Defekte</a></li>
              <li class="dropdown active">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Postdienst <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="postdienst.php">Neues Paket</a></li>
                <li class="active"><a href="#.php">Pakete bearbeiten</a></li>
              </ul>
            </li>
              <li><a href="pakete.php">Pakete<?php

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
        <?php
        //Hinweise & Meldungen
        if($src == 'del'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Paket wurde erfolgreich gelöscht.</div>";
        } else if($src == 'paket'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Paketort wurde erfolgreich geändert.</div>";
        }
        ?>
        <h1>Pakete bearbeiten</h1><br>
        <?php

        //Alle aktuellen Pakete mit Link zum Bearbeiten
         $sql = "SELECT id, ort, zeitpunkt, schueler_uid FROM paket WHERE aktuell=1 ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

         while($row = mysqli_fetch_assoc($result)){
            $ort = $row['ort'];
            $id = $row['id'];
            $zeitpunkt = $row['zeitpunkt'];
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $zeitpunkt);
            $zeitpunkt = $date -> format('d.m.Y');
             $uid = $row['schueler_uid'];

             //ein Panel für jedes aktuelle Paket
             echo "<div class='panel panel-info'>
             <div class='panel-heading'>
    <h3 class='panel-title'>Paket für $uid<span aria-hidden='true'><a href='pakete_bearbeitung.php?id=$id' class='close'><span class='glyphicon glyphicon-pencil'></span></a></span></h3>
  </div><div class='panel-body'>";

             echo "Datum: <strong>$zeitpunkt</strong> <br>Ort: <strong>$ort</strong></div></div>";
         }

        ?>

    </div>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>
