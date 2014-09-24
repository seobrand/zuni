<?php

	// Your subject
	$subject='New Contact Inquiry';// From
	$message = 'The person that contacted you is';

		
require_once('class.phpmailer.php');
$mail             = new PHPMailer();
$body             = eregi_replace("[\]",'',$message);
$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPAuth   = true;                  		// enable SMTP authentication
// sets the prefix to the servier
//$mail->Host       = "smtp.gmail.com";      		// sets GMAIL as the SMTP server
//$mail->Port       = 465;                  	 	// set the SMTP port for the GMAIL server
//$mail->Username   = "office@mahimagroup.org";  	// GMAIL username
//$mail->Password   = "officemahima111";		// GMAIL password


		$mail->Port       = 25;                    // set the SMTP server port
		$mail->Host       = "mail.zuni.com"; // SMTP server
		$mail->Username   = "noreply@zuni.com";     // SMTP server username
		$mail->Password   = "taal789";            // SMTP server password
		
		//$mail->Username   = "abc@zuni.com";     // SMTP server username
		//$mail->Password   = "vishal";            // SMTP server password
		$email = 'manoj@planetwebsolution.com';
		$mail->SetFrom($email, 'Keshav Sharma');
		$mail->Subject    = $subject;
		$mail->AltBody    = "To view the message, please use an HTML compatidble email viewer!"; // optional, comment out and test
		$mail->MsgHTML($body);
		$address = 'keshav@planetwebsolution.com';
		$mail->AddAddress($address, "Mahima Group");
		if(!$mail->Send()) {
		  echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		  echo $result = 'sent';
		}
?>