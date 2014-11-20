<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	include_once 'gump/gump.php';
	include_once 'phpass/phpass.php';

	class installer{

		// connect to DB
		public function databaseConnect(){
			
			$db_host 	 = 'localhost';
			$db_user 	 = 'root';
			$db_password = 'sjsm123456';
			$db_name 	 = 'my_db';
			
			$connection = new mysqli($db_host, $db_user, $db_password, $db_name);
			
			if ( $connection->connect_errno ) {
				
				return $connection->connect_error;
			} else {

				return $connection;
			}
		}

		// escape string for MySql
		public function requestEscape($escapeString, $connection = '') {
			
			if( $connection == '' ) {

				$connection = $this->databaseConnect();	
			}

			if ( $escapedString = $connection->real_escape_string($escapeString) ) {
				
				return $escapedString;
			}

			return $connection->error;
		}
	}

	class user extends installer{
		private $username;
		private $password;
		private $ID;

		// Create table users
		public function install(){

			$connection =  $this->databaseConnect();

			if ( $queryCreateUserTable = $connection->prepare("CREATE TABLE users (ID Integer PRIMARY KEY NOT NULL AUTO_INCREMENT, username CHAR(99), password CHAR(99) )") ) {
				$tableUsersCreated = $queryCreateUserTable->execute();

				if ( $tableUsersCreated === true ) {
					
					return true;
				}
			}

			return $connection->error;
		}

		//Delete user table
		public function uninstall(){
			$connection = $this->databaseConnect();

			if ( $queryUnInstalTable = $connection->prepare("DROP TABLE users;") ) {
				$tableUsersDeleted = $queryUnInstalTable->execute();

				if ( $tableUsersDeleted == true) {

					return true;
				}
			}

			return $connection->error;
		}

		// Create new user with requested username and password, return user ID
		public function create($requestedUsername, $requestedPassword){
			
			$userCreateError = $this->validateUser(false, $requestedUsername, $requestedPassword);

			if ( $userCreateError == false && is_array($userCreateError) == false ) {
				
				$connection = $this->databaseConnect();

				if ( $queryAddUser = $connection->prepare("INSERT INTO users (username, password) VALUES (?, ?)") ) {

					$queryAddUser->bind_param('ss', $escapedUsername, $hashPassword);

					$escapedUsername = $this->requestEscape($requestedUsername, $connection);
					$hashPassword = $this->makePassword( $this->requestEscape($requestedPassword, $connection) );

					if ( $queryAddUser->execute() ) {

						$this->ID       = $connection->insert_id;
						$this->username = $escapedUsername;
						$this->password = $requestedPassword;

						return $this->ID;
					}
				}

				return $connection->error;
			}

			return $userCreateError; 
		}

		//return array with user information 
		public function read(){
			
			$userData = array(
				'username' => $this->username,
				'password' => $this->password,
				'ID' => $this->ID,
			);
			
			return $userData;
		}

		// change user variables and row in DB
		public function update($userID, $newName, $newPassword){

			$userUpdateError = $this->validateUser(false, $newName, $newPassword);

			if ( $userUpdateError == false && is_array($userUpdateError) == false ) {
				
				$connection = $this->databaseConnect();

				if ( $queryUpdateUser = $connection->prepare("UPDATE `users` SET `username` = ?, `password` = ? WHERE ID = ?") ) {
					
					$queryUpdateUser->bind_param('ssi', $escapedUsername, $hashPassword, $userID);
					
					$escapedUsername = $this->requestEscape($newName, $connection);
					$hashPassword    = $this->makePassword( $this->requestEscape($newPassword, $connection) );
					
					
					if ( $userUpdated = $queryUpdateUser->execute() ){
						
						$this->username = $newName;
						$this->password = $newPassword;

						return true;
					}
				}

				return $connection->error;
			} else {

				return ( is_array($userUpdateError) ) ? $userUpdateError : false;
			}
		}

		// unset user variables and remove row from DB
		public function destroy($userID){
			
			$connection = $this->databaseConnect();

			if ( $queryDeleteUser = $connection->prepare("DELETE FROM `users` WHERE ID = ?") ) {
				$queryDeleteUser->bind_param('i', $userID);

				if ( $userDeleted = $queryDeleteUser->execute() ) {
					
					unset($this->username, $this->password, $this->ID);
					
					return true;
				}
			}
				
			return $connection->error;
		}
		
		// returns array of users in users tables
		public function listRows(){
			
			$connection = $this->databaseConnect();

			if ( $queryListRows = $connection->prepare("SELECT * FROM users") ) {
				
				if ( $rowList = $queryListRows->execute() ) {

					$queryListRows->bind_result($ID, $username, $password);

					$rowArray = array();
					
					while ( $queryListRows->fetch() ) {
						
						$rowFields = array(
							'ID'       => $ID,
							'username' => $username,
							'password' => $password,
							);
						
						$rowArray[] = $rowFields;
					}

					return $rowArray;
				} 

				return $rowList;
			}

			return $connection->error;
		}

		/**
		 * @param  [boolean]	$validatePassword  [validate password]
		 * @param  [string]		$requestedUsername [username to check for]
		 * @param  [string] 	$requestedPassword [password to check for]
		 * @return [boolean]
		 */
		public function validateUser($validatePassword, $requestedUsername, $requestedPassword = ''){
				
			$validateData = array(
				'username' => $requestedUsername,
				'password' => $requestedPassword,
			);
			
			$validateRules = array( 'username' => 'required|valid_email', );
			
			if ( $validatePassword == '' || $validatePassword == true ) {
				
				$validateRules['password'] = 'required|alpha_and_numeric|max_len,100|min_len,6';
			}

			$isValid = GUMP::is_valid($validateData, $validateRules);
			if ( $isValid === true ) {

				$hashPassword = $this->makePassword( $this->requestEscape($requestedPassword) );
				
				$userList = $this->listRows();

				$connection = $this->databaseConnect();
				
				if ( $queryFindUser = $connection->prepare("SELECT `ID` FROM `users` WHERE `username` = ?") ) {
					$queryFindUser->bind_param('s', $requestedUsername);
					
					$queryFindUser->execute();
					$queryFindUser->bind_result($userID);
					$queryFindUser->fetch();

				} else {

					return $connection->error;
				}

				$validUser = false;
				if ( $userID !== null) {

					foreach ( $userList as $value ) {

						if ( $validatePassword == false ) {

							if ( $value['username'] == $requestedUsername && $value['ID'] == $userID ) {

								$validUser = true;
							}
						} else {

							if ( $value['username'] == $requestedUsername && $value['password'] == $hashPassword && $value['ID'] == $userID ) {

								$validUser = true;
							}
						}
					}
				}
				// return true if provided user and/or password match else return false
				return $validUser; 
			}

			//return error array
			return $isValid; 
		}

		private function makePassword($passwordRequest) {

			// Base-2 logarithm of the iteration count used for password stretching
			$hash_cost_log2 = 8;
			// Do we require the hashes to be portable to older systems (less secure)?
			$hash_portable = true;

			$hasher = new PasswordHash($hash_cost_log2, $hash_portable);

			$hash = $hasher->HashPassword($passwordRequest);

			return $hash;
		}
	}
?>