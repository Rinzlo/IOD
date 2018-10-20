<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/SMTP.php';
mb_language("english");
mb_internal_encoding("UTF-8");

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'comp490');

if(isset($_POST['email'])){
	$email = $_POST['email'];
	$dbh = new PDO('mysql:dbname=comp490;host=localhost;charset=utf8', DB_USERNAME, DB_PASSWORD);
	$query = 'select * from user_info';
	$stmt = $dbh->query($query);
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($result['Email'] == $email){
			$mail = new PHPMailer(true);
			try{
				//Server settings
				$mail->SMTPDebug = 2;
				$mail->isSMTP();
				$mail->Host = "smtp.gmail.com";
				$mail->SMTPAuth = true;
				$mail->Username = "testmail.comp490";
				$mail->Password = "exmpdcqgublmapao";
				$mail->SMTPSecure = "src";
				$mail->Port = 587;
				
				//Recipients
				$mail->setFrom("testmail.comp490@gmail.com", "Meme Team");
				$mail->addAddress($email, $result['Username']);
				
				//Content
				$mail->isHTML(true);
				$mail->Subject = "Password Reset Requested";
				$mail->Body = "Your password reset request was accepted.<br>
								Please access to the following link.<br>
								<a href=http://localhost/spa.html#resetPasswordPage>http://localhost/spa.html#resetPasswordPage</a>";
				$mail->AltBody = "Your password reset request was accepted. 
								Please access to the following link. 
								http://localhost/spa.html#resetPasswordPage";
				
				//Send email
				$mail->send();
				
				die("Message has been sent.");
			}catch(Exception $e){
				die('<error> Message could not be sent. Mailer Error');
			}
		}
	}
	die("<error> The email is not registered.");
}
?>