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
require_once(dirname(__FILE__).'/config.inc');
require_once(dirname(__FILE__).'/vendor/autoload.php');
require_once(dirname(__FILE__).'/PDO.php');

/**
 * UTC is the easiest to work with especially for something that deals with
 * time from potentially multiple time zones.
 */
date_default_timezone_set('UTC');

/**
 * Database PDO connection
 */
$pdo = new \PDO('mysql:host={$dbhost};port=3306;dbname={$db}', $dbuser, $dbpassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/**
 * Mapping PHP errors to exceptions. This is done to allow SabreDAV to
 * become aware of errors and send the proper HTTP result code back to the
 * client
 */
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

/**
 * The backends.
 * The $authBackend has been written to use a SHA256 hash for password
 * verification for users. The rest of the backends are currently stock
 * sabre/dav
 */
$auth = new \HCC\PDO($pdo);
$principal = new \Sabre\DAVACL\PrincipalBackend\PDO($pdo);
$caldav = new \Sabre\CalDAV\Backend\PDO($pdo);
$carddav = new \Sabre\CardDAV\Backend\PDO($pdo);

/**
 * The directory tree
 *
 * Basically this is an array which contains the 'top-level' directories in the
 * WebDAV server.
 */
$tree = [
    new \Sabre\CalDAV\Principal\Collection($principal),
    new \Sabre\CalDAV\CalendarRoot($principal, $caldav),
    new \Sabre\CardDAV\AddressBookRoot($principal, $carddav),
];

// The object tree needs in turn to be passed to the server class
$dav = new \Sabre\DAV\Server($tree);
$dav->setBaseUri('/');

/*
 * I suspect several of these plugin's are not strictly required but until
 * I have verified that I am leaving them in here.
 */
$dav->addPlugin(new \Sabre\DAV\Auth\Plugin($auth));
$dav->addPlugin(new \Sabre\DAVACL\Plugin());
$dav->addPlugin(new \Sabre\CalDAV\Plugin());
$dav->addPlugin(new \Sabre\CardDAV\Plugin());
$dav->addPlugin(new \Sabre\CalDAV\Subscriptions\Plugin());
$dav->addPlugin(new \Sabre\CalDAV\Schedule\Plugin());
$dav->addPlugin(new \Sabre\DAV\Sync\Plugin());
$dav->addPlugin(new \Sabre\DAV\Browser\Plugin());

/*
 * Now that everything is setup run the server
 */
$dav->exec();
