<?php
    session_start();
    if(isset($_SESSION['uid'])){
        $role = $_SESSION['role'];
        header("Location: $role.php");
} 
?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Digitales Austragebuch - Login</title>
</head>
<body role="document" class="form">
    <div><br><br></div>
    <?php
        $err = '';
        if(!empty($_GET['err'])){
            $err = $_GET["err"];
        }
    ?>
    <div class="container theme-showcase" role="main">

            <div class="row">
                <div class="col-lg-4 center-block col-md-6  col-sm-8">
                    <h2>Einloggen</h2>
                    <?php
                        if($err == 'login'){
                            echo "<div class='alert alert-danger' role='alert'>Bitte gültige Werte eingeben.</div>";
                        } else if($err == "user"){
                            echo "<div class='alert alert-danger' role='alert'>Benutzername oder Passwort sind inkorrekt.</div>";
                        }
                    ?>
                    <form method="post" action="processing/loginp.php">
                        <input type="text" class="form-control" placeholder="Benutzername" name="uid">
                        <input type="password" class="form-control" placeholder="Passwort" name="pwd"><br>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                    </form>
                </div>
                
            </div>
    </div>
</body>
</html>