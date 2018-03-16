<?php
    session_start();
    if(!isset($_SESSION['uid'])){
    header("Location: index.php");
} 
if($_SESSION['role'] != 'admin'){
    header("Location: logout.php");
}
include 'dbh.php';
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
                        } 
                    ?>
                    <form method="post" action="schuelerregisterp.php">
                        <input type="text" class="form-control" placeholder="WG" name="wg"><br>
                        <!--<label>SozPäd: 
                            <select name="soz">
                                <?php
                                    $sql = "SELECT * FROM user WHERE role='sozpaed'";
                                    $result = mysqli_query($conn, $sql);
                                
                                while($row = mysqli_fetch_assoc($result)){
                                    $uid = $row['uid'];
                                    $first = $row['first'];
                                    $last = $row['last'];
                                    echo "<option value='$uid'>$first $last</option>";
                                }
                                ?>
                            </select><br><br>
                        </label>--><br>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Okay</button>
                    </form>
                </div>
                
            </div>
    </div>
</body>
</html>