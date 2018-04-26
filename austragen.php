<?php
    session_start();
    if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
    } 
    $err = '';
    if(!empty($_GET['err'])){
        $err = $_GET["err"];
    }
if($err == 'date'){
    $wohin = $_GET['wohin'];
    $back = $_GET['back'];
    $absprache = $_GET['absprache'];
}
?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Austragen</title>
</head>
<body role="document">
    <div class="container theme-showcase" role="main">
<br><br>
            <div class="row">
                <a href="schueler.php"><span class="glyphicon glyphicon-home" style="font-size: 2em;" aria-hidden="true"></span></a>
                <div class="col-lg-4 center-block col-md-6 col-sm-8">
                    <h2>Austragen</h2>
                    <?php
                        if($err == 'empty'){
                            echo "<div class='alert alert-danger' role='alert'>Bitte gültige Werte eingeben.</div>";
                        }
                    ?>
                    <form method="post" action="processing/austragenp.php">
                        <input type="text" class="form-control" placeholder="Wohin?" name="wohin"
                               <?php if($err == 'date') echo " value=$wohin"; ?>>
                        <input type='datetime' class="form-control" placeholder="Wann zurück?" name="back"<?php if($err == 'date') echo " value=$back id='wrong'"; ?>>
                        <input type="text" class="form-control" placeholder="Absprache?" name="absprache"<?php if($err == 'date') echo " value=$absprache"; ?>><br>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                    </form>
                </div>
                
            </div>
    </div>
</body>
</html>