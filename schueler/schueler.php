<?php
//Tests: Ist der Nutzer angemeldet und ein Schüler?
    session_start();
    include 'dbh.php';
    if(!isset($_SESSION['uid'])){
        header("Location: index.php");
    }
    if($_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
    }
//Hinweise & Meldungen
    $src = '';
    if(!empty($_GET['src'])){
        $src = $_GET['src'];
    }
    $uid = $_SESSION['uid'];
    //Erneuerung von Daten, die extern geändert werden könnten
    $sql = "SELECT * FROM schueler WHERE uid='$uid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $_SESSION['postdienst'] = $row['postdienst'];
    $_SESSION['ausgetragen'] = $row['ausgetragen'];
    $first = $_SESSION['first'];
    
    $ausgetragen = $_SESSION['ausgetragen'];
?>
<html>
<head>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <!-- Bootstrap-Theme -->
    <title>Willkommen</title>
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
          <a class="navbar-brand" href="#">Austragebuch</a>
        </div>

        <!-- Alle Navigationslinks, Formulare und anderer Inhalt werden hier zusammengefasst und können dann ein- und ausgeblendet werden -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Start <span class="sr-only">(aktuell)</span></a></li>
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
              <?php
              //individueller Menüpunkt Postdienst
                  if($_SESSION['postdienst']){
                      echo '<li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Postdienst <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="postdienst.php">Neues Paket</a></li>
                <li><a href="post_bearbeitung.php">Pakete bearbeiten</a></li>
              </ul>
            </li>';

                  }
                  ?>

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
        <br>
        <?php
        //Hinweise und Meldungen
        if($src == 'austragen'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Du wurdest erfolgreich ausgetragen.</div>";
        } else if($src == 'zurücktragen'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Du wurdest erfolgreich zurückgetragen.</div>";
        } else if($src == 'gast'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Gast wurde erfolgreich angemeldet.</div>";
        } else if($src == 'pwd'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Passwort wurde erfolgreich geändert.</div>";
        } else if($src == 'defekt'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Defekt wurde erfolgreich gemeldet.</div>";
        } else if($src == 'defektproblem'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Es ist ein Fehler aufgetreten, Defekt konnte nicht gemeldet werden.</div>";
        } else if($src == 'telegram'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Deine Telegram-ID wurde erfolgreich gespeichert.</div>";
        }
        ?>
        <!-- Jumbotron auf Begrüßungsseite -->
        <div class="jumbotron">
            <h1><?php
                echo "Hallo, $first.";
	           ?>
            </h1>
            <p>
                Willkommen im digitalen Austragebuch.
                <?php
                if($ausgetragen){
                    echo "Du bist zurzeit ausgetragen.";
                } else{
                    echo "Du bist nicht ausgetragen.";
                }
                ?>
            </p>
        </div>
        <?php

        //Hinweise auf unbestätigte Besuchsankündigungen
        $sql = "SELECT name, zeitraum FROM gast WHERE schueler_uid='$uid' AND bestaetigt=0";
        $result = mysqli_query($conn, $sql);

        while($row = mysqli_fetch_assoc($result)){
            $name = $row['name'];
            $zeitraum = $row['zeitraum'];
            echo "<div class='alert alert-warning alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button><strong>Hinweis!</strong> Dein Besuch von $name im Zeitraum $zeitraum wurde noch nicht bestätigt.</div>";
        }

        ?>
        <div class="row">

        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>
