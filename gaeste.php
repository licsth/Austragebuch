<?php
    session_start();
    include 'dbh.php';
    if(!isset($_SESSION['uid'])){
        header("Location: index.php");
    }
    if($_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
    }
    $src = '';
    if(!empty($_GET['src'])){
        $src = $_GET['src'];
    }
    $first = $_SESSION['first'];
    $uid = $_SESSION['uid'];
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
            <li class="active"><a href="#">Besuchsankündigungen</a></li>
              <li><a href="defekte.php">Mängel &amp; Defekte</a></li>
               <?php
                  if($_SESSION['postdienst']){
                      echo "<li><a href='postdienst.php'>Postdienst</a></li>";
                  }
                  ?>
              <li><a href="pakete.php">Pakete</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $uid; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="password.php">Passwort ändern</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="logout.php">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    
    <div class="container theme-showcase" role="main">
        
        <h1>Deine Besuchsankündigungen</h1><br>
        <?php
        
        //SQL-Befehl: wähle die Daten aller Gäste, die vom aktuelle angemeldeten Schüler angemeldet wurden und die aktuell sind (1 bedeutet als boolean true), ordne diese nach abfallender ID
        $sql = "SELECT name, zeitraum, bestaetigt, id FROM gast WHERE schueler_uid='$uid' AND aktuell=1 ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        
        //Solange es ein weiteres Ergebnis (row) gibt, wähle dieses aus und...
        while($row = mysqli_fetch_assoc($result)){
            //...speichere Namen, Zeitraum, und ob er bestätigt ist
            $name = $row['name'];
            $zeitraum = $row['zeitraum'];
            $bestaetigt = $row['bestaetigt'];
            $id = $row['id'];
            
            //gib ein Panel mit den Informationen aus
            echo "<div class='panel panel-info'><div class='panel-heading'>
    <h3 class='panel-title'>$zeitraum <span aria-hidden='true'><a href='processing/aktuell.php?id=$id' class='close'>&times;</a></span></h3>
  </div><div class='panel-body'>";
            
            if($bestaetigt) echo "<strong>Bestätigter</strong> ";
            else echo "<strong>Unbestätigter</strong> ";
                    
            echo "Besuch von <strong>$name</strong>.
                </div></div>";
        }
        
        ?>
        <div class="row">
            
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>