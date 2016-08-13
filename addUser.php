#!/usr/bin/php
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

	echo "This was development code that will eventually be removed".PHP_EOL;
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
