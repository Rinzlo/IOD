<?php

try {
 
	$pdo = new PDO('mysql:dbname=comp490;host=localhost;charset=utf8', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "success connecting DB";
 
} catch (PDOException $e) {
 
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    die("errorE");
}
?>
