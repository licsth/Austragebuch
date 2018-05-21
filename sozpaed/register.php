<?php
//Sind Zugriffsberechtigungen gegeben?
session_start();
if(!isset($_SESSION['uid'])){
    header("Location: index.php");
    return;
}
if($_SESSION['role'] != 'sozpaed'){
    header("Location: processing/logout.php");
    return;
}
//Hinweise und Meldungen
$src = '';
    if(!empty($_GET['src'])){
        $src = $_GET['src'];
    }
?><html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Schüler registrieren</title>
    <script>
        //Hochgeladene Datei erhalten
        function getFile(){
        document.getElementById("upfile").click();
    }
        function sub(obj){
    document.getElementById("btn").click();
    //event.preventDefault();
  }
    </script>
</head>
<body role="document" class="form">
    
    <div class="container theme-showcase" role="main">
        <br>
        <a href="sozpaed.php"><span class="glyphicon glyphicon-home" style="font-size: 2em;" aria-hidden="true"></span></a><br>
            <div class="col-lg-6 col-md-9 col-sm-10 center-block">
                <h2 class="hi">Schüler registrieren</h2><?php
                //Fehlermeldungen
                if($src == 'type'){
            echo "<div class='alert alert-danger' role='alert'>Falsches Dateiformat. Bitte eine Datei mit der Endung <code>.csv</code> hochladen.</div>";
        }?>
                <p>Mit diesem Tool kannst du Schüler über einen CSV-Import registrieren. CSV-Dateien sind Dateien, die Excel-Tabellen als Text wiedergeben. Nutze den Import, indem du eine .csv-Datei hochlädst, die zu registrierende Nutzer nach dem Schema "Vorname; Nachname; WG" enthält. <br>Du kannst eine solche Datei in Excel mit der Funktion <code>Datei > Speichern unter</code> mit dem Dateiformat .csv erstellen.</p>
                <!-- Formular zum hochladen von Dateien -->
                <form enctype="multipart/form-data" method="post" action="processing/register.php">
                    <div class="form-group">
                        <div class="btn btn-default" id="yourBtn" onclick="getFile()"><span class="glyphicon glyphicon-paperclip">  </span>  Hier die .csv-Datei hochladen</div>
                        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                        <input type="file" id="upfile" class="fileField" style="opacity:0; width:0; height: 0;" name="file" onchange="sub(this)">
                        </div>
                    <button id="btn" class="btn btn-lg btn-primary btn-block" style="opacity:0; width:0; height: 0;" type="submit">Okay</button>
                </form>
            </div>
        </div>
</body>
</html>