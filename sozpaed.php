<?php
    session_start();
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
?>
<html>
<head>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap-Theme -->
    <title>Willkommen</title>
</head>
<body role="document">
    <br>
    
    <div class="container theme-showcase" role="main">
        <br>
        <?php
        if($src == 'pwd'){
            echo "<div class='alert alert-success' role='alert'>Passwort erfolgreich geändert.</div>";
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
            <div class="col-lg-3 col-sm-6">
            <form action="austragebuch.php?show=all">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Austragebuch</button>
            </form>
            </div>
            <div class="col-lg-3 col-sm-6">
            <form action="password.php">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Passwort ändern</button>
            </form>
            </div>
            <div class="col-lg-3 col-sm-6">
            <form action="logout.php">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Logout</button>
            </form>
            </div>
            
        </div>
    </div>
</body>
</html>