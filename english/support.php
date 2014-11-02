
<?php
$_POST["email"]<>'';
    $ToEmail = 'support@checkd.it';
    $EmailSubject = 'Site contact form';
    $subject = "Emne: " . $_REQUEST['tittel'] . " ";
    $mailheader = "From: ".$_POST["email"]."\r\n";
    $mailheader .= "Reply-To: ".$_POST["email"]."\r\n";
    $mailheader .= "Content-type: text/html; charset=utf-8\r\n";

    $MESSAGE_BODY .= "Supportmelding fra avsender: ".$_POST["melding"]."";

    mail($ToEmail, $subject, $MESSAGE_BODY, $mailheader) or die ("Failure");
    echo '<script type="text/javascript">alert("Vi har mottatt din melding og vil besvare den snarest mulig / We have receiced your message and will answer it shortly."); window.location="index-en.html";</script>';

?>