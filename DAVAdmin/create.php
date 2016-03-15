<?php
	$username = filter_input(INPUT_REQUEST, 'username');
	$displayname = filter_input(INPUT_REQUEST, 'displayname');
	$email = filter_input(INPUT_REQUEST, 'email', FILTER_VALIDATE_EMAIL);
	$password = filter_input(INPUT_REQUEST, 'password');
	
	$users = "INSERT INTO `users` SET `username` = '$username', `password` = hash('sha256', $password)";
	$prin1 = "INSERT INTO `principals` SET `uri` = 'principals/$username', `email`='$email', `displayname`='$displayname'";
	$prin2 = "INSERT INTO `principals` SET `uri` = 'principals/$username/calendar-proxy-read'";
	$prin3 = "INSERT INTO `principals` SET `uri` = 'principals/$username/calendar-proxy-write'";
		