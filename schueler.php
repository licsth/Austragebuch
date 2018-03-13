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

$sql = "SELECT ausgetragen FROM schueler WHERE uid='$uid'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$ausgetragen = $row['ausgetragen'];
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
        if($src == 'austragen'){
            echo "<div class='alert alert-success' role='alert'>Du wurdest erfolgreich ausgetragen.</div>";
        } else if($src == 'zurücktragen'){
            echo "<div class='alert alert-success' role='alert'>Du wurdest erfolgreich zurückgetragen.</div>";
        }
        ?>
        <div class="jumbotron">
            <h1><?php
                echo "Hallo, $first.";
	           ?>
            </h1>
            <p>
                Willkommen im digitalen Austragebuch. 
                <?php
                if($ausgetragen){
                    echo "Du bist zurzeit ausgetragen.";
                } else{
                    echo "Du bist nicht ausgetragen.";
                }
                ?>
            </p>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <?php
                if(!$ausgetragen){
                    echo "<form action='austragen.php'>
                        <button class='btn btn-lg btn-primary btn-block' type='submit'>Austragen</button>
                    </form>";
                } else{
                    echo "<form action='zurücktragen.php'>
                        <button class='btn btn-lg btn-primary btn-block' type='submit'>Zurücktragen</button>
                    </form>";
                }
                ?>
            </div>
            <div class="col-lg-3">
            <form action="password.php">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Passwort ändern</button>
            </form>
            </div>
            <div class="col-lg-3">
            <form action="logout.php">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Logout</button>
            </form>
            </div>
            
        </div>
    </div>
</body>
</html>