<?php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'comp490');
if(isset($_POST['username'], $_POST['password'])){
	$user = $_POST['username'];
	$pass = $_POST['password'];
	$dbh = new PDO('mysql:dbname=comp490;host=localhost;charset=utf8', DB_USERNAME, DB_PASSWORD);
	$query = 'select * from user_info';
	$stmt = $dbh->query($query);
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($result['Username'] == $user){
			$query = 'update user_info set Password = :password where Username = :username';
			$stmt = $dbh->prepare($query);
			$params = array(':password' => $pass, ':username' => $user);
			$stmt->execute($params);
			die("success");
		}
	}
	die("<error> Username or Password is not correct.");
}else{
	die("<error> Required information are not provided.");
}
?>