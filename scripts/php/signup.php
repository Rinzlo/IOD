<?php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'comp490');
if(isset($_POST['email'], $_POST['username'], $_POST['password'])){
	$email = $_POST['email'];
	$user = $_POST['username'];
	$pass = $_POST['password'];
	$dbh = new PDO('mysql:dbname=comp490;host=localhost;charset=utf8', DB_USERNAME, DB_PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = 'select * from user_info';
	$stmt = $dbh->query($query);
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($result['Username'] == $user){
			die("<error> This Username is already used.");
		}else if($result['Email'] == $email){
			die("<error> This Email is already registered.");
		}
	}
	$stmt = $dbh->prepare("INSERT INTO user_info (Email, Username, Password) VALUES (:email, :username, :password)");
	$params = array(':email' => $email, ':username' => $user, ':password' =>$pass);
	$stmt->execute($params);
	die("Registration succeeded");
}
die("error");
?>