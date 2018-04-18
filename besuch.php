<?php
    session_start();

include 'dbh.php';
if(!isset($_SESSION['uid'])){
    header("Location: index.php");
}
if($_SESSION['role'] != 'sozpaed'){
    header("Location: logout.php");
}
$uid = $_SESSION['uid'];

?>
<html>
<head>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap-Theme -->
    <title>Besuchsankündigungen</title>
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
            <li><a href="sozpaed.php">Start <span class="sr-only">(aktuell)</span></a></li>
            <li><a href="austragebuch.php?show=all">Austragebuch</a></li>
            <li class="active"><a href="#">Besuchsankündigungen</a></li>
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
        <?php
        if(!empty($_GET['src'])){
            if($_GET['src'] == "besuch"){
                echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Besuch wurde erfolgreich bestätigt.</div>";
            }
        }
        ?>
        <div class="page-header">
  <h1>Unbestätigte Besuchsankündigungen (<?php
                
                $sql = "SELECT COUNT(name) FROM gast WHERE bestaetigt=0";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                echo $row['COUNT(name)'];
                
                ?>)</h1>
</div>
    
        <br>
        <?php
        $sql = "SELECT * FROM gast WHERE bestaetigt=0";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['id'];
            $name = $row['name'];
            $zeitraum = $row['zeitraum'];
            $uid = $row['schueler_uid'];
            $antrag = $row['antrag'];
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $antrag);
            $antrag = $date->format('D, d. M y, H:i') . " Uhr";

            $sql2 = "SELECT * FROM user WHERE uid='$uid'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

            $first = $row2['first'];
            $last = $row2['last'];

            echo "<div class='panel panel-default'>
            <div class='panel-heading'>
    <h3 class='panel-title'>Besuch für $first $last</h3>
  </div><div class='panel-body'><strong>Von:</strong> $name<br><strong>Zeitraum:</strong> $zeitraum
          <br><strong>Eingereicht:</strong> $antrag<br><br><a href='processing/besuchp.php?id=$id' class='btn btn-default'>Bestätigen</a>
          </div>
        </div>
        
        ";
            
            
        }
        ?>
            
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>