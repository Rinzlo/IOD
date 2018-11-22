<?php
declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
	
	/**
	 * Send an email
	 * @param string $to
	 * @param string $subject
	 * @param string $text
	 * @param string $html
	 * @throws \PHPMailer\PHPMailer\Exception
	 */
    public static function send(string $to, string $subject, string $text, string $html)
    {
        //Create a new PHPMailer instance
        $mail = new PHPMailer;

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = Config::SMTP_DEBUG;

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = Config::MAIL_USERNAME;

        //Password to use for SMTP authentication
        $mail->Password = Config::MAIL_PASSWORD;

        //Set who the message is to be sent from
        $mail->setFrom(Config::MAIL_FROM);

        // Set who the message is to be sent to without their name
        $mail->addAddress($to);

        //Set the subject line
        $mail->Subject = $subject;

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
//        $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        //Replace the plain text body with one created manually
        // body stuff
        $mail->msgHTML($html);
        $mail->AltBody = $text;

        //Attach an image file
//        $mail->addAttachment('images/phpmailer_mini.png');

        //send the message, check for errors
        if (!$mail->send()) {
            Flash::addMessage("Mailer Error: " . $mail->ErrorInfo, Flash::WARNING);
        } else {
            Flash::addMessage("Message sent, check your spam folder!", Flash::SUCCESS);
        }
    }
}