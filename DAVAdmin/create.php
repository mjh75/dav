<?php
	$username = filter_input(INPUT_POST, 'username');
	$displayname = filter_input(INPUT_POST, 'displayname');
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
	$password = filter_input(INPUT_POST, 'password');
	
	$users = "INSERT INTO `users` SET `username` = ':username', `password` = hash('sha256', ':password')";
	$prin1 = "INSERT INTO `principals` SET `uri` = 'principals/:username', `email`=':email', `displayname`=':displayname'";
	$prin2 = "INSERT INTO `principals` SET `uri` = 'principals/:username/calendar-proxy-read'";
	$prin3 = "INSERT INTO `principals` SET `uri` = 'principals/:username/calendar-proxy-write'";

	try {
		$dbh = new PDO("mysql:host=localhost;dbname=dav", "dav", "plantronics");
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	$sth1 = $dbh->prepare($users);
	$data = array('username' => $username, 'password' => $password);
	$sth1->execute($data);
	unset($data);
	
	$sth2 = $dbh->prepare($prin1);
	$data = array('username' => $username, 'email' => $email, 'displayname' => $displayname);
	$sth2->execute($data);
	unset($data);
	
	$sth3 = $dbh->prepare($prin2);
	$data = array('username' => $username);
	$sth3->execute($data);
	unset($data);

	$sth4 = $dbh->prepare($prin3);
	$data = array('username' => $username);
	$sth4->execute($data);
	unset($data);
	
	header("Location: accounts.php");