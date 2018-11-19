<?php
//Not working correctly.

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailerを配置するパスを自身の環境に合わせて修正
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/SMTP.php';
mb_language("english");
mb_internal_encoding("UTF-8");

$email = "seiya.yamamoto.574@my.csun.edu";
$dbh = new PDO('mysql:dbname=comp490;host=localhost;charset=utf8', 'root', 'root');
$query = 'select * from user_info';
$stmt = $dbh->query($query);
while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($result['Email'] == $email){
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'testmail.comp490';                 // SMTP username
    $mail->Password = 'exmpdcqgublmapao';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to (ssl:465)

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('seiya.yamamoto.574@my.csun.edu', 'Joe User');     // Add a recipient
    $mail->addAddress('pornofan_tetra7717@yahoo.co.jp');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
		die("success");
	}
}
die("<error> The email is not registered.");

?>