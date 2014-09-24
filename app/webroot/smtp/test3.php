<?php
require_once('class.phpmailer.php');

$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->SMTPSecure = "tls";
$mail->Host = "mail.zuni.com";  // specify main and backup server
$mail->Port = 25;
$mail->Username = "noreply@zuni.com";  // SMTP username
$mail->Password = "taal789"; // SMTP password

$mail->From = "manoj@planetwebsolution.com";
$mail->FromName = "manoj";
$mail->AddAddress("keshav@planetwebsolution.com", "keshav");

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "Here is the subject";
$mail->Body    = "This is the HTML message body <b>in bold!</b>";
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}

echo "Message has been sent";
?>