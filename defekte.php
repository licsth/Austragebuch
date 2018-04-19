<?php
    $empfaenger = "linda.thelen@arcor.de";
    $betreff = "Die Mail-Funktion";
    $from = "From: Austragebuch Service <austragebuch@hansenberg.info>\n";
    $from .= "Reply-To: austragebuch@hansenberg.info\n";
    $from .= "Content-Type: text/html\n";
    $text = "Hier lernt Ihr, wie man mit PHP Mails verschickt";

    mail($empfaenger, $betreff, $text, $from);
?>