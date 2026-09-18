<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
require '../../../../includes/third-party/PHPMailer/class.phpmailer.php';
//require 'PHPMailer/class.phpmailer.php';
require '../../../../includes/third-party/PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'relay.nic.in';  // Specify main and backup SMTP servers
$mail->SMTPAuth = false;                               // Enable SMTP authentication
$mail->Username = 'support.priemp-wb@gov.in';                 // SMTP username
$mail->Password = 'PRD@2016';                           // SMTP password
                           // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                    // TCP port to connect to

//$mail->setFrom('noreply@wbswpension.gov.in', 'WBSWPENSION');
$mail->addAddress('mannasourav.manna1@gmail.com', 'sourav');     // Add a recipient
$mail->addAddress('s.mahajan@nic.in');               // Name is optional
//$mail->addReplyTo('jksh21@gmail.com', 'Information');
$mail->addCC('roydibakar10@gmail.com');
//$mail->addBCC('subhajitmaity.haldia@gmail.com');

  // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'PRD test';
$mail->Body    = 'test';

//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}