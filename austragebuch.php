<?php
    session_start();

include 'dbh.php';
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

$show = 'all';
if(!empty($_GET['show'])){
    $show = $_GET['show'];
}

$first = $_SESSION['first'];

/*$von = "";
if(!empty($_SESSION['von'])){
    $von = $_SESSION['von'];
}

$bis = 'CURDATE() + 1';
if(!empty($_SESSION['bis'])){
    $bis = $_SESSION['bis'];
}*/
?>
<html>
<head>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap-Theme -->
    <title>Austragebuch</title>
</head>
<body role="document">
    <br>
    <div class="container theme-showcase" role="main">
        <a href="sozpaed.php" class="btn btn-sm btn-default">Home</a><br><br>
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
                <?php
                if($show == 'all'){
                    echo "Dir werden alle Einträge der letzten zwei Tage angezeigt.";
                } else{
                    echo "Dir werden alle nicht zurückgetragenen Einträge angezeigt.";
                }
                ?>
            </p>
        </div>
        <div class="row">
            <div class="col-md-3">
            <span><input type="checkbox" id="alleweg" onchange="change()" <?php
               if($show == 'away'){
                   echo "class='checked' checked";
               }
               ?>
                         > Nur nicht zurückgetragene</span></div>
            <!--<div class="col-md-9">
                <div class="col-md-2">Zeitraum: </div>
                <div class="col-md-10">
                    <form action="austragebuchp.php" method="post">
                        <div class="col-md-4"><input type="text" name="von" placeholder="Von" class="form-control" <?php
                        /*if($von != ''){
                            echo "value='$von'";
                        }                             
                        ?>
                        ></div>
                        <div class="col-md-4"><input type="text" name="bis" placeholder="Bis" class="form-control"<?php
                        if($bis != 'CURDATE() + 1'){
                            echo "value='$bis'";
                        } */                            
                        ?>></div>
                        <div class="col-md-2"><input type="submit" value="OK" class="btn btn-primary"></div>
                    </form>
                </div>
            </div>-->
        </div>
        <br>
        <table class="table">
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
                if($show == 'all'){
                    $sql = "SELECT * FROM eintrag WHERE away > CURDATE() - 2 ORDER BY id DESC";
                } else{
                    $sql = "SELECT * FROM eintrag WHERE isback IS NULL ORDER BY id DESC";
                }
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $id = $row['id'];
                    $uid = $row['uid'];
                    
                    $sql2 = "SELECT * FROM user WHERE uid='$uid'";
                    $result2 = mysqli_query($conn, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);
                    
                    $first = $row2['first'];
                    $last = $row2['last'];
                    
                    $wg = $row['wg'];
                    $away = $row['away'];
                    $back = $row['back'];
                    $absprache = $row['absprache'];
                    $wohin = $row['wohin'];
                    $isback = $row['isback'];
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
    <script src="main.js" type="text/javascript"></script>
</body>
</html>