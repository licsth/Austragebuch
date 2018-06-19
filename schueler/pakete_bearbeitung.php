<?php
//Prüfungen:
//Ist der Nutzer angemeldet?
//Ist der Nutzer ein Schüler?
//Ist der Schüler vom Postdienst?
    session_start();
    include 'dbh.php';

    if(!isset($_SESSION['uid'])){
        header("Location: logout.php");
    }
    if($_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
    }
    if(!$_SESSION['postdienst']){
        header("Location: logout.php");
    }
    if(empty($_GET['id'])){
        header("Location: logout.php");
        return;
    }
    $id = $_GET['id'];
    $uid = $_SESSION['uid'];
    //Erneuerung von Daten, die extern geändert werden könnten
    $sql = "SELECT * FROM schueler WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $_SESSION['postdienst'] = $row['postdienst'];
    $_SESSION['ausgetragen'] = $row['ausgetragen'];

    $sql = "SELECT * FROM paket WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$schueler = $row['schueler_uid'];
$ort = $row['ort'];

$sql = "SELECT * FROM schueler WHERE uid='$schueler'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$name = $row['first'] . " " . $row['last'];

    $ausgetragen = $_SESSION['ausgetragen'];
    $uid = $_SESSION['uid'];

//Fehlermeldungen
    $err = '';
if(!empty($_GET['err'])){
    $err = $_GET['err'];
}

?>
<html>
<head>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Pakete bearbeiten</title>
    <link href="style.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script>
      //Bestätigungs-Popup wenn ein Paket gelöscht werden soll
      function del(){
          if(confirm("Möchtest du wirklich das Paket für <?php echo $name; ?> löschen?")){
              window.location = 'processing/paket_delete.php?id=<?php echo $id; ?>';
          }
      }

    </script>
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
              <li><a href="defekte.php">Mängel &amp; Defekte</a></li>
              <li class="dropdown active">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Postdienst <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="postdienst.php">Neues Paket</a></li>
                <li class="active"><a href="post_bearbeitung.php">Pakete bearbeiten</a></li>
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
        <h1>Pakete bearbeiten</h1><br>
        <!-- Formular, um ein Paket zu bearbeiten, enthält aktuelle Daten des Pakets -->
        <form class="form center-block col-md-8" action="processing/pakete_bearbeitung.php?id=<?php echo $id; ?>" method="post">
            <fieldset disabled>
            <div class="form-group">
                <label for="schueler" class="control-label">Schüler</label>
                <input name="schueler" type="text" class="form-control" value="<?php echo $name; ?>">
            </div>
            </fieldset>
            <div class="form-group">
                <label for="ort" class="control-label">Ort des Pakets</label>
                <input name="ort" type="text" value="<?php echo $ort; ?>" class="form-control
                                                                    <?php
                                                                                 //Falls vorige Angabe eine Fehlermeldung verursacht hat
                                                                    if($err=='ort'){
                                                                        echo " bg-danger";
                                                                    }
                                                                    ?>">
            </div>
            <button type="submit" class="btn btn-primary">Ändern</button>
            <!--<a href='processing/paket_delete.php?id=<?php echo $id; ?>' class="btn btn-danger">Paket löschen</a>-->
            <button onclick='del()' type="button" class="btn btn-danger">Paket löschen</button>
        </form>

    </div>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>
