<?php
	include_once("../config.inc");
	$username = filter_input(INPUT_POST, 'username');
	$displayname = filter_input(INPUT_POST, 'displayname');
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
	$password = filter_input(INPUT_POST, 'password');
	
	$users = "INSERT INTO `users` SET `username` = :username, `password` = sha2(:password, 256)";
	$prin1 = "INSERT INTO `principals` SET `uri` = CONCAT('principals/', :username), `email`=:email, `displayname`=:displayname";
	$prin2 = "INSERT INTO `principals` SET `uri` = CONCAT('principals/', :username, '/calendar-proxy-read')";
	$prin3 = "INSERT INTO `principals` SET `uri` = CONCAT('principals/', :username, '/calendar-proxy-write')";

	try {
		$dbh = new PDO("mysql:host=localhost;dbname=dav", $dbuser, $dbpassword);
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	$sth1 = $dbh->prepare($users);
	$sth1->bindParam(':username', $username);
	$sth1->bindParam(':password', $password);
	$sth1->execute();
	
	$sth2 = $dbh->prepare($prin1);
	$sth2->bindParam(':username', $username);
	$sth2->bindParam(':email', $email);
	$sth2->bindParam('displayname', $displayname);
	$sth2->execute();
	
	$sth3 = $dbh->prepare($prin2);
	$sth3->bindParam(':username', $username);
	$sth3->execute();

	$sth4 = $dbh->prepare($prin3);
	$sth4->bindParam(':username', $username);
	$sth4->execute();
	
	header("Location: accounts.php");