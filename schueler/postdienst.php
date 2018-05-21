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
    if(!$_SESSION['postdienst']){
        header("Location: logout.php");
    }
    $ausgetragen = $_SESSION['ausgetragen'];
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
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Postdienst</title>
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                //individueller Menüpunkt Austragen / zurücktragen
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
                <li class="active"><a href="#.php">Neues Paket</a></li>
                <li><a href="post_bearbeitung.php">Pakete bearbeiten</a></li>
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
        <h1>Postdienst</h1><br>
        <?php
        //Hinweise & Meldungen
        if($src == 'paket'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Paket wurde erfolgreich registriert.</div>";
        } else if($src == 'paketerr'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Paket konnte nicht registriert werden.</div>";
        }
        
        ?>
        <!-- Formular, um Pakete hinzuzufügen -->
        <form class="form center-block col-md-8" action="processing/paketep.php" method="post">
            <div class="form-group">
                <label for="schueler" class="control-label">*Schüler</label><br>
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
        
            <div class="form-group">
                <label for="ort" class="control-label">Paketort (falls nicht in deiner WG)</label>
                <input name="ort" type="text" class="form-control">
            </div>
            
            <button type="submit" class="btn btn-default">Okay</button>
        </form>
        
    </div>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>