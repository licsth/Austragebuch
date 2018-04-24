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
                      echo "<li><a href='postdienst.php'>Postdienst</a></li>";
                  }
                  ?>
              <li class="active"><a href="#">Pakete</a></li>
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
    
    <!-- Hauptcontainer, hier alle Inhalte der Seite einfügen -->
    <div class="container theme-showcase" role="main">
        <h1>Pakete</h1>
        
    </div>
    <!-- Skripts: jQuery zum Ansteuern von Elementen auf der Seite und Bootstrap-Skript zum Beispiel für Dropdown-Menüs -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>