<?php
    session_start();
//Ist der SozPäd angemeldet?
include 'dbh.php';
if(!isset($_SESSION['uid'])){
    header("Location: index.php");
}
if($_SESSION['role'] != 'sozpaed'){
    header("Location: processing/logout.php");
}

$show = 'all';
if(!empty($_GET['show'])){
    $show = $_GET['show'];
}

//Anzahl der letzten Tage, sofern alle Einträge gezeigt werden
$anzahl = 2;
if(!empty($_GET['anzahl'])){
    $anzahl = $_GET['anzahl'];
}

$first = $_SESSION['first'];

$uid = $_SESSION['uid'];
?>
<html>
<head>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <!-- Bootstrap-Theme -->
    <title>Austragebuch</title>
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
            <li class="active"><a href="#">Austragebuch</a></li>
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
              <li><a href="register.php">Nutzer registrieren</a></li>
              <li><a href="postdienst.php">Postdienst hinzufügen</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $uid; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="password.php">Passwort ändern</a></li>
                <li><a href="schueler_passwort.php">Schülerpasswort</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="logout.php">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    <div class="container theme-showcase" role="main">
        <div class="page-header">
            <h1>Austragebuch<small>


                <?php
                //Informationen zur aktuellen Anzeige
                if($show == 'all'){
                    echo "Einträge der letzten Tage";
                } else{
                    echo "Nicht zurückgetragene Einträge";
                }
                ?>
            </small></h1>
        </div>
        <div class="row">
            <div class="col-md-3">
                <!-- Checkbox, um nach ausgetragenen Schülern zu filtern -->
            <span><input type="checkbox" id="alleweg" onchange="change()" <?php
               if($show == 'away'){
                   echo "class='checked' checked";
               }
               ?>
                         > Nur nicht zurückgetragene</span></div>

            <?php
            if($show == 'all'){
                //Anzahl der letzten Tage, sofern alle Einträge angezeigt werden
                echo '
            <div class="col-md-4">
                <form class="form-inline" action="processing/anzahltage.php" method="get">Anzahl anzuzeigender Tage: <input type="number" value="' . $anzahl . '" style="width:3em" name="anzahl"> <input  class="btn btn-default" style="transform:scale(.8)" type="submit" value="Ok"></form>
            </div>';
            }
            ?>
        </div>
        <br>
        <!-- Tabelle, die die Einträge enthält -->
        <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>WG</th>
                <th>Wohin?</th>
                <th>Wann weg?</th>
                <th>Wann zurück?</th>
                <th>Absprache</th>
                <th>Zurück</th>
              </tr>
            </thead>
            <tbody>
                <?php
                $sql = '';
                //Einträge aus der Datenbank auswählen
                if($show == 'all'){
                    $sql = "SELECT * FROM eintrag WHERE away > CURDATE() - $anzahl ORDER BY id DESC";
                } else{
                    $sql = "SELECT * FROM eintrag WHERE isback=0 OR isback IS NULL ORDER BY id DESC";
                }
                $result = mysqli_query($conn, $sql);
                //Für jeden Eintrag eine neue Zeile:
                while($row = mysqli_fetch_assoc($result)){
                    $id = $row['id'];
                    $uid = $row['uid'];

                    $sql2 = "SELECT * FROM schueler WHERE uid='$uid'";
                    $result2 = mysqli_query($conn, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);

                    $first = $row2['first'];
                    $last = $row2['last'];
                    $wg = $row2['wg'];

                    $away = $row['away'];
                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $away);
                    $en = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    $deshort = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

                    //Formatierung des Datums
                    //Nur Uhrzeit für heutige Einträge
                    if($date->format('d.m.Y') == date('d.m.Y')){
                        $away = "Heute, " . $date->format('H:i') . " Uhr";
                    }
                    //Wochentag und Uhrzeit für Einträge dieser Woche
                    else if(intval(DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->diff($date)->format('%d')) < 6){
                        $away = $date->format('D, H:i') . " Uhr";
                        $away = str_replace($en, $deshort, $away);
                    }
                    //Komplettes Datum für alle anderen Einträge
                    else{
                        $away = $date->format('D, d.m.Y, H:i') . " Uhr";
                        $away = str_replace($en, $deshort, $away);
                    }

                    $back = $row['back'];
                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $back);
                    //Formatierung des Datums
                    //Nur Uhrzeit für heutige Einträge
                    if($date->format('d.m.Y') == date('d.m.Y')){
                        $back = "Heute, " . $date->format('H:i') . " Uhr";
                    }
                    //Wochentag und Uhrzeit für Einträge dieser Woche
                    else if(intval(DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->diff($date)->format('%d')) < 6){
                        $back = $date->format('D, H:i') . " Uhr";
                        $back = str_replace($en, $deshort, $back);
                    }
                    //Komplettes Datum für alle anderen Einträge
                    else{
                        $back = $date->format('D, d.m.Y, H:i') . " Uhr";
                        $back = str_replace($en, $deshort, $back);
                    }

                    $absprache = $row['absprache'];
                    $wohin = $row['wohin'];
                    $isback = $row['isback'];
                    //Zeile in der Tabelle
                    echo "<tr>
                    <td>$id</td><td>$first $last</td><td>$wg</td><td>$wohin</td><td>$away</td><td>$back</td><td>$absprache</td><td>";
                    if($isback){
                        echo "<span class='glyphicon glyphicon-ok'></span>";
                    }
                    echo "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
    function change(){
        //Checkbox im Austragebuch verarbeiten
        if(!document.getElementById('alleweg').classList.contains('checked')){
            window.location = 'austragebuch.php?show=away';
        } else{
            window.location = 'austragebuch.php?show=all';
        }
    }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>
