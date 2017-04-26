<?php
/*$to      = 'kangchengyong91@gmail.com';
$subject = 'the subject';
$message = 'Fuck you hahahahahahaha';
$headers = 'From: vivicrew@iotadev.com';
mail($to, $subject, $message, $headers);*/
require '../vendor/autoload.php';

$mail = new PHPMailer;

//Enable SMTP debugging.                         
//Set PHPMailer to use SMTP.
$mail->isSMTP();            
//Set SMTP host name                          
$mail->Host = "smtp.zoho.com";
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;                          
//Provide username and password     
$mail->Username = "vivicrew@iotadev.com";                 
$mail->Password = 'P@$$w0rd';                           
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";                           
//Set TCP port to connect to 
$mail->Port = 587;                                   

$mail->From = "vivicrew@iotadev.com";
$mail->FromName = "Vivicrew";

$mail->addAddress("kangchengyong91@gmail.com", "Kang Cheng Yong");

$mail->isHTML(true);

$mail->Subject = "Subject Text";
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    echo "Message has been sent successfully";
}
?>
