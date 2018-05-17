<?php
    session_start();

include 'dbh.php';

if(empty($_POST['mangel']) && empty($_POST['ort'])){
    header("Location: ../defekte.php?err=mo");
} else if(empty($_POST['mangel'])){
    header("Location: ../defekte.php?err=mangel");
}
else if(empty($_POST['ort'])){
    header("Location: ../defekte.php?err=ort");
} else{

    $first = $_SESSION['first'];
    $last = $_SESSION['last'];
    $wg = $_SESSION['wg'];
    
    $sql = "SELECT * FROM wg WHERE id='$wg'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    $sozpaed = $row['sozpaed'];
    $mentor = $row['mentor'];
    
$mangel = $_POST['mangel'];
$ort = $_POST['ort'];
$bemerkung = $_POST['bemerkung'];
    
    $empfaenger = "linda.thelen@arcor.de";
    $betreff = "Mängel und Defekte";
    $from = "From: Austragebuch Service <austragebuch@hansenberg.info>\n";
    $from .= "Reply-To: austragebuch@hansenberg.info\n";
    $from .= "Content-Type: text/html\n";
    
    $text = "Name: $first $last<br>
    WG: $wg<br>
    Sozialpädagoge: $sozpaed<br>
    Mentor: $mentor<br>
    Datum: " . date('D, d.m.Y, h:i') . " Uhr<br>
    Mangel: $mangel<br>
    Ort: $ort <br>";
    
    if($bemerkung){
        $text .= "Bemerkung: $bemerkung";
    }
    //echo $text;
    $mail = mail($empfaenger, $betreff, $text, $from);
    if($mail){
        header("Location: ../schueler.php?src=defekt");
    } else{
        header("Location: ../schueler.php?src=defektproblem");
    }
}?>
