<?php





function mail_function($mail_body,$email,$emp_sub){
	
	
	$mail = new PHPMailer;
		
//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
//$mail->Host = 'relay dot nic dot in';  // Specify main and backup SMTP servers
$mail->SMTPAuth = false;                               // Enable SMTP authentication
//$mail->Username = 'supportpriempwbgovin'; 

//Mail cheng Vulnerabilities                 // SMTP username
//$mail->Password = 'PRD2016';                           // SMTP password
                           // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                    // TCP port to connect to

//Mail cheng Vulnerabilities 
$mail->addAddress('gopeswarmnicin', 'Sir');     // Add a recipient


  // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $emp_sub;
$mail->Body    =$mail_body;

//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

/*if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}*/
$mail->send();
}