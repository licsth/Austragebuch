<?php
$src = '';
    if(!empty($_GET['src'])){
        $src = $_GET['src'];
    }
?><html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="style2.css" rel="stylesheet" type="text/css">
    <title>Nutzer registrieren</title>
    <script>
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
            <div class="col-lg-4 col-md-6 col-sm-8 center-block">
                <h2 class="hi">Nutzer registrieren</h2><?php
                if($src == 'type'){
            echo "<div class='alert alert-danger' role='alert'>Falsches Dateiformat. Bitte eine Datei mit der Endung <code>.csv</code> hochladen.</div>";
        }?>
                <form enctype="multipart/form-data" method="post" action="processing/register.php">
                    <div class="form-group">
                        <div class="btn btn-default" id="yourBtn" onclick="getFile()"><span class="glyphicon glyphicon-paperclip">  </span>  Hier die .csv-Datei hochladen</div>
                        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                        <input type="file" id="upfile" class="fileField" draggable="true" name="file" onchange="sub(this)">
                        </div>
                    <button id="btn" class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                </form>
            </div>
        </div>
</body>
</html>