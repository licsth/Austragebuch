<?php
    session_start();
    if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'schueler'){
        header("Location: logout.php");
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
    <?php
        $err = '';
        if(!empty($_GET['err'])){
            $err = $_GET["err"];
        }
    ?>
    <div class="container theme-showcase" role="main">
<a href="schueler.php" class="btn btn-sm btn-default">Home</a><br><br>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-4 col-sm-8 col-sm-offset-3">
                    <h2>Austragen</h2>
                    <?php
                        if($err == 'empty'){
                            echo "<div class='alert alert-danger' role='alert'>Bitte gültige Werte eingeben.</div>";
                        }
                    ?>
                    <form method="post" action="austragenp.php">
                        <input type="text" class="form-control" placeholder="Wohin?" name="wohin">
                        <input type='datetime' class="form-control" placeholder="Wann zurück?" name="back">
                        <input type="text" class="form-control" placeholder="Absprache?" name="absprache"><br>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                    </form>
                </div>
                
            </div>
    </div>
</body>
</html>