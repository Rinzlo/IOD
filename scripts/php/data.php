<?php
if($_POST['deviceid'] == "light"){
	$on = $_POST['on'];
	if($on == 'true'){
		die("Light is turned on.");
	}else{
		die("Light is turned off.");
	}
}
?>