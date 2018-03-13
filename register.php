<?php
    session_start();
    if(!isset($_SESSION['uid'])){
    header("Location: index.php");
} 
if($_SESSION['role'] != 'admin'){
    header("Location: logout.php");
}
?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Person registrieren</title>
</head>
<body role="document">
    <?php
        $err = '';
        if(!empty($_GET['err'])){
            $err = $_GET["err"];
        }
    ?>
    <div class="container theme-showcase" role="main">
        <a href="admin.php" class="btn btn-sm btn-default">Home</a>
            <div class="row">
                <div class="middle">
                    <h2>Person hinzufügen</h2>
                    <?php
                        if($err == 'register'){
                            echo "<div class='alert alert-danger' role='alert'>Bitte gültige Werte eingeben.</div>";
                        } else if($err == "uid"){
                            echo "<div class='alert alert-danger' role='alert'>Benutzername bereits vergeben.</div>";
                        }
                    ?>
                    <form method="post" action="registerp.php">
                        <label>Rolle: 
                            <select name="role">
                                <option value="schueler" selected>Schüler</option>
                                <option value="sozpaed">SozPäd</option>
                                <option value="admin">Admin</option>
                            </select>
                        </label>
                        <input type="text" class="form-control" placeholder="Vorname" name="first">
                        <input type="text" class="form-control" placeholder="Nachname" name="last"><br>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                    </form>
                </div>
                
            </div>
    </div>
</body>
</html>