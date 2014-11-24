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

				if ( $tableUsersCreated === TRUE ) {
					
					return TRUE;
				}
			}

			return $connection->error;
		}

		//Delete user table
		public function uninstall(){
			$connection = $this->databaseConnect();

			if ( $queryUnInstalTable = $connection->prepare("DROP TABLE users;") ) {
				$tableUsersDeleted = $queryUnInstalTable->execute();

				if ( $tableUsersDeleted == TRUE) {

					return TRUE;
				}
			}

			return $connection->error;
		}

		// Create new user with requested username and password, return user ID
		public function create($requestedUsername, $requestedPassword){
			
			$userCreateError = $this->validateUser(FALSE, $requestedUsername, $requestedPassword);

			// if FALSE, username not taken
			// if array provided info invalid
			if ( $userCreateError == FALSE && is_array($userCreateError) == FALSE ) {
				
				$connection = $this->databaseConnect();

				if ( $queryAddUser = $connection->prepare("INSERT INTO users (username, password) VALUES (?, ?)") ) {

					$queryAddUser->bind_param('ss', $escapedUsername, $hashPassword);

					$escapedUsername = $this->requestEscape($requestedUsername, $connection);
					
					$hasher = new PasswordHash(8, TRUE);
					$hashPassword = $hasher->HashPassword($requestedPassword);

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

			$userUpdateError = $this->validateUser(FALSE, $newName, $newPassword);

			// if FALSE, username not taken
			// if array provided info invalid
			if ( $userUpdateError == TRUE && is_array($userUpdateError) == FALSE ) {
				
				$connection = $this->databaseConnect();

				if ( $queryUpdateUser = $connection->prepare("UPDATE `users` SET `username` = ?, `password` = ? WHERE ID = ?") ) {
					$queryUpdateUser->bind_param('ssi', $escapedUsername, $hashPassword, $userID);
					
					$escapedUsername = $this->requestEscape($newName, $connection);
					
					$hasher = new PasswordHash(8, TRUE);
					$hashPassword = $hasher->HashPassword($newPassword);
					
					if ( $userUpdated = $queryUpdateUser->execute() ){
						
						$this->username = $newName;
						$this->password = $newPassword;

						return TRUE;
					}
				}

				return $connection->error;
			} else {

				//username is taken $userUpdateError provides FALSE TRUE 
				return ( is_array($userUpdateError) ) ? $userUpdateError : FALSE;
			}
		}

		// unset user variables and remove row from DB
		public function destroy($userID){
			
			$connection = $this->databaseConnect();

			if ( $queryDeleteUser = $connection->prepare("DELETE FROM `users` WHERE ID = ?") ) {
				$queryDeleteUser->bind_param('i', $userID);

				if ( $userDeleted = $queryDeleteUser->execute() ) {
					
					unset($this->username, $this->password, $this->ID);
					
					return TRUE;
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
			
			if ( $validatePassword == '' || $validatePassword == TRUE ) {
				
				$validateRules['password'] = 'required|alpha_and_numeric|max_len,100|min_len,6';

				$hasher = new PasswordHash(8, TRUE);
				$hashPassword =	$hasher->HashPassword($requestedPassword);
			}

			$isValid = GUMP::is_valid($validateData, $validateRules);
			if ( $isValid === TRUE ) {
	
				$userList = $this->listRows();

				$validUser = FALSE;
				// loop through existing users
				foreach ( $userList as $value ) {

					if ( $validatePassword == FALSE ) {
						
						// if username and ID match, means user is updating password not username
						// if not match is username is taken by a different user
						if ( $value['username'] == $requestedUsername ) {

							$validUser = TRUE;
						}
					} else {

						if ( $value['username'] == $requestedUsername && $value['password'] == $hashPassword ) {

							$validUser = TRUE;
						}
					}
				}
				
				// return TRUE if provided user and/or password match else return FALSE
				return $validUser; 
			}

			//return error array
			return $isValid; 
		}
	}
?>