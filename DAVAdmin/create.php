<?php
/* 
 * Copyright (C) 2016 Michael J. Hartwick <hartwick at hartwick.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
	include_once(dirname(__FILE__)."/config.inc");
	$username = filter_input(INPUT_POST, 'username');
	$displayname = filter_input(INPUT_POST, 'displayname');
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
	$password = filter_input(INPUT_POST, 'password');
	
	$users = "INSERT INTO `users` SET `username` = :username, `password` = sha2(:password, 256)";
	$prin1 = "INSERT INTO `principals` SET `uri` = CONCAT('principals/', :username), `email`=:email, `displayname`=:displayname";
	$prin2 = "INSERT INTO `principals` SET `uri` = CONCAT('principals/', :username, '/calendar-proxy-read')";
	$prin3 = "INSERT INTO `principals` SET `uri` = CONCAT('principals/', :username, '/calendar-proxy-write')";
	$cal = "INSERT INTO `calendars` SET `principaluri`=CONCAT('principals/', :username), `displayname`='Calendar', `uri`='calendar', `components`='VEVENT,VTODO'";
	$address = "INSERT INTO `addressbooks` SET `principaluri`=CONCAT('principals/', :username), `displayname` = 'Address Book', `uri` = 'addressbook'";

	try {
		$dbh = new PDO("mysql:host={$dbhost};dbname={$db}", $dbuser, $dbpassword);
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

	$sth5 = $dbh->prepare($cal);
	$sth5->bindParam(':username', $username);
	$sth5->execute();

	$sth6 = $dbh->prepare($address);
	$sth6->bindParam(':username', $username);
	$sth6->execute();
	
	header("Location: accounts.php");
