<?php
//Server Address
/*$SmtpServer="mail.zuni.com";
$SmtpPort="25"; //default
$SmtpUser="noreply@zuni.com";
$SmtpPass="hungry123456";*/

$SmtpServer="zuni.com";
$SmtpPort="25"; //default
$SmtpUser="noreply@zuni.com";
$SmtpPass="taal789";
?>

<?php
class SMTPClient
{

function SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body)
{
$this->SmtpServer = $SmtpServer;
$this->SmtpUser = base64_encode ($SmtpUser);
$this->SmtpPass = base64_encode ($SmtpPass);
$this->from = $from;
$this->to = $to;
$this->subject = $subject;
$this->body = $body;
$this->newLine = "\r\n";

if ($SmtpPort == "") 
{
$this->PortSMTP = 25;
}
else
{
$this->PortSMTP = $SmtpPort;
}
}

function SendMail ()
{
if ($SMTPIN = fsockopen ($this->SmtpServer, $this->PortSMTP)) 
{
fputs ($SMTPIN, "EHLO ".$HTTP_HOST."\r\n"); 
$talk["hello"] = fgets ( $SMTPIN, 1024 ); 
fputs($SMTPIN, "auth login\r\n");
$talk["res"]=fgets($SMTPIN,1024);
fputs($SMTPIN, $this->SmtpUser."\r\n");
$talk["user"]=fgets($SMTPIN,1024);
fputs($SMTPIN, $this->SmtpPass."\r\n");
$talk["pass"]=fgets($SMTPIN,256);

fputs ($SMTPIN, "MAIL FROM: <".$this->from.">\r\n"); 
$talk["From"] = fgets ( $SMTPIN, 1024 ); 
fputs ($SMTPIN, "RCPT TO: <".$this->to.">\r\n"); 
$talk["To"] = fgets ($SMTPIN, 1024); 

fputs($SMTPIN, "DATA\r\n");
$talk["data"]=fgets( $SMTPIN,1024 );
 
//Construct Headers
$headers = "MIME-Version: 1.0" . $this->newLine;
$headers .= "Content-type: text/html; charset=iso-8859-1" . $this->newLine;
$headers .= "From: <".$this->from.">". $this->newLine;
$headers .= "To: <".$this->to.">". $this->newLine;
$headers .= "Bcc: ". $this->newLine;
$headers .= "Subject: ".$this->subject. $this->newLine;

fputs($SMTPIN, $headers."\r\n\r\n".$this->body."\r\n.\r\n");

$talk["send"]=fgets($SMTPIN,256);


//CLOSE CONNECTION AND EXIT ... 
fputs ($SMTPIN, "QUIT\r\n"); 
fclose($SMTPIN); 
// 
} 
return $talk;
} 
}
?>
<?php
$from = 'noreply@zuni.com';
$to = 'keshav@planetwebsolution.com';	//'keshavhello1@gmail.com';//
$subject = "Hello World Again!";
$body = "Hello! World,<br><p> i am CHINTI... speed 1.3 THz.</p>";
$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body);
$SMTPChat = $SMTPMail->SendMail();
echo '<pre>';
print_r($SMTPChat);
echo '</pre>';
?>