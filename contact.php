
<?php
$_POST["email"]<>'';
    $ToEmail = 'registration@checkd.it';
    $EmailSubject = 'New registration from';
    $mailheader = "From: ".$_POST["email"]."\r\n";
    $mailheader .= "Reply-To: ".$_POST["email"]."\r\n";
    $mailheader .= "Content-type: text/html; charset=iso-8859-1\r\n";

    $MESSAGE_BODY .= "Email: ".$_POST["email"]."";

    mail($ToEmail, $EmailSubject, $MESSAGE_BODY, $mailheader) or die ("Failure");
    echo '<script type="text/javascript"> window.location="registered.html";</script>';

?>