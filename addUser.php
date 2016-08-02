#!/usr/bin/php
<?php
	echo "Username: ";
	$username = trim(fgets(STDIN));

	echo "Name: ";
	$name = trim(fgets(STDIN));

	echo "Email Address: ";
	$email = trim(fgets(STDIN));

	echo "Password: ";
	$password = trim(fgets(STDIN));
	$hpass = hash('sha256', $password);

	$fh = fopen("add.sql", "a");
	fputs($fh, "USE dav;\r\n");
	fputs($fh, "INSERT INTO `users` SET `username`='$username', `password`='$hpass';\r\n");
	fputs($fh, "INSERT INTO `principals` SET `uri`='principals/$username', `email`='$email', `displayname`='$name';\r\n");
  fputs($fh, "INSERT INTO `calendars` SET `principaluri` = 'principals/$username', `displayname` = 'Calendar', `uri` = 'calendar', `components` = 'VEVENT,VTODO';\r\n");
  fclose($fh);
