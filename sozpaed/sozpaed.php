<?php
    session_start();
include 'dbh.php';
if(!isset($_SESSION['uid'])){
    header("Location: index.php");
}
if($_SESSION['role'] != 'sozpaed'){
    header("Location: logout.php");
}
$src = '';
if(!empty($_GET['src'])){
    $src = $_GET['src'];
}
$first = $_SESSION['first'];
$uid = $_SESSION['uid'];
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
            <li><a href="austragebuch.php?show=all">Austragebuch</a></li>
            <li><a href="besuch.php">Besuchsankündigungen 
                <?php
                
                $sql = "SELECT COUNT(name) FROM gast WHERE bestaetigt=0";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $count = $row['COUNT(name)'];
                if($count > 0){
                    echo "<span class='badge'>$count</span>";
                }
                
                ?>
                </a></li>
              <li><a href="register.php">Schüler registrieren</a></li>
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
        <br>
        <?php
        if($src == 'pwd'){
            echo "<div class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Passwort wurde erfolgreich geändert.</div>";
        } else if($src == 'index'){
            echo "<div class='alert alert-info alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button>Bitte denke daran, dein Passwort zu ändern, um die Sicherheit deines Accounts zu gewähren.</div>";
        }
        ?>
        <div class="jumbotron">
            <h1><?php
                echo "Hallo, $first.";
	           ?>
            </h1>
            <p>
                Willkommen im digitalen Austragebuch.
            </p>
        </div>
        <div class="row">
            
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>