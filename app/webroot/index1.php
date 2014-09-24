<?php
$to = "keshav@planetwebsolution.com, seobranddevelopers@gmail.com";
$subject = "HTML email";

$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

// More headers
$headers .= 'From: <manoj@planetwebsolution.com>' . "\r\n";
$headers .= 'Cc:  manoj@planetwebsolution.com' . "\r\n";

if(mail($to,$subject,$message,$headers)) {
 echo 'yes';
} else {
	echo 'no';
}
?>