<?php /** * This example shows settings to use when sending via Google's Gmail servers. */
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
//date_default_timezone_set('Etc/UTC');
//require 'PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
//$mail->Host = 'smtp.gmail.com';
$mail->Host = 'mail.jhm.co.nz';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

$mail->SMTPOptions = array(
	'ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true,
	),
);

//Username to use for SMTP authentication - use full email address for gmail
//$mail->Username = "pixl.harish@gmail.com";
$mail->Username = "noreply@jhm.co.nz";

//Password to use for SMTP authentication
//$mail->Password = "pass@word#12";
$mail->Password = "JHM2015";

//Set who the message is to be sent from
//$mail->setFrom('pixl.harish@gmail.com', siteName);
$mail->setFrom('noreply@jhm.co.nz', siteName);

//Set an alternative reply-to address
//$mail->addReplyTo('pixl.harish@gmail.com', siteName);
$mail->addReplyTo('noreply@jhm.co.nz', siteName);

//Set who the message is to be sent to
$mail->addAddress($phpMailerTo, 'Customer');

//Set the subject line
$mail->Subject = $phpMailerSubject;

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
$mail->msgHTML($phpMailerText);

//Replace the plain text body with one created manually
$mail->AltBody = ' ';

//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
//$mail->send();
if (!$mail->send()) {
	error_log($mail->ErrorInfo, 0);
	echo "Mailer Error: " . $mail->ErrorInfo;die();
} else {
//echo "Message sent!";
	//die();
}