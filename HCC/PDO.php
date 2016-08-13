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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.	If not, see <http://www.gnu.org/licenses/>.
 */

namespace HCC;

/**
 * This is an authentication backend that uses a database to manage passwords.
 *
 * @copyright Copyright (C) 2016 Michael J. Hartwick <hartwick at hartwick.com>
 * @author Michael J. Hartwick <hartwick at hartwick.com>
 * @license GPL3
 */
class PDO extends \Sabre\DAV\Auth\Backend\AbstractBasic {
		protected $pdo;
		protected $tableName = 'users';

		/**
		 * Creates the backend object.
		 *
		 * @param PDO $pdo
		 */
		public function __construct(\PDO $pdo) {
				$this->pdo = $pdo;
		}

		/**
		 * Validates a users credentials
		 *
		 * @param string $username
		 * @param string $password
		 * @return boolean
		 */
		public function validateUserPass($username, $password) {
			$retval = \FALSE;
			$stmt = $this->pdo->prepare('SELECT `password` FROM `'.$this->tableName.'` WHERE `username` = ?');
			$stmt->execute(array($username));
			if($stmt->fetchColumn() == hash('sha256', $password)) {
				$retval = \TRUE;
			}
			$stmt->closeCursor();
			return $retval;
		}
}
