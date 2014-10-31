<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

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
		public function requestEscape($escapeString){
			
			$connection = $this->databaseConnect();

			$escapedString = $connection->real_escape_string($escapeString);

			return $escapedString;
		}
	}

	class user extends installer{
		private $username;
		private $password;
		private $ID;

		// Create table users
		public function install(){

			$connection =  $this->databaseConnect();

			$queryCreateUserTable = $connection->prepare("CREATE TABLE users (ID Integer PRIMARY KEY NOT NULL AUTO_INCREMENT, username CHAR(99), password CHAR(99) )");

			$tableUsersCreated = $queryCreateUserTable->execute();

			if ( $tableUsersCreated === true ) {
				
				return true;
			} else {

				return $connection->error;
			}
		}

		//Delete user table
		public function uninstall(){
			$connection = $this->databaseConnect();
			
			$queryUnInstalTable = $connection->prepare("DROP TABLE users;");
			$tableUsersDeleted = $queryUnInstalTable->execute();
			
			if ( $tableUsersDeleted == true) {
				
				return true;
			} else{

				return $connection->error;
			}
		}

		// Create new user with requested username and password, return user ID
		public function create($requestedUsername, $requestedPassword){
			
			$connection = $this->databaseConnect();

			$queryAddUser = $connection->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
			$queryAddUser->bind_param('ss', $escapedUsername, $escapedPassword);

			$escapedUsername = $this->requestEscape($requestedUsername);
			$escapedPassword = $this->requestEscape($requestedPassword);

			$userExist = $this->validateUser(false, $requestedUsername, $requestedPassword);
			$userAdded = ( $userExist == false )? $queryAddUser->execute() : false;
			
			if( $userAdded === true ) {

				$this->ID = $connection->insert_id;
				$this->username = $escapedUsername;
				$this->password = $escapedPassword;

				return $this->ID;
			} else {

				return $connection->error;
			}
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
		public function update($userID ,$newName, $newPassword){

			$connection = $this->databaseConnect();

			$queryUpdateUser = $connection->prepare("UPDATE `users` SET `username` = ?, `password` = ? WHERE ID = ?");
			$queryUpdateUser->bind_param('ssi', $escapedUsername, $escapedPassword, $userID);

			$escapedUsername = $this->requestEscape($newName);
			$escapedPassword = $this->requestEscape($newPassword);
			
			$userUpdated = $queryUpdateUser->execute();
			if ( $userUpdated === true ){
				
				$this->username = $newName;
				$this->password = $newPassword;

				return true;
			} else {

				return $connection->error;
			}
		}

		// unset user variables and remove row from DB
		public function destroy($userID){
			
			$connection = $this->databaseConnect();

			$queryDeleteUser = $connection->prepare("DELETE FROM `users` WHERE ID = ?");
			$queryDeleteUser->bind_param('i', $userID);

			$userDeleted = $queryDeleteUser->execute();
			if ( $userDeleted === true ) {
				
				unset($this->username);
				unset($this->password);
				unset($this->ID);
				
				return true;
			} else {

				return $connection->error;
			}
		}
		
		// returns array of users in users tables
		public function listRows(){
			
			$connection = $this->databaseConnect();

			$queryListRows = $connection->prepare("SELECT * FROM users");
			
			$rowList = $queryListRows->execute();
			if( $rowList ) {

				$queryListRows->bind_result($ID, $username, $password);

				$rowArray = array();
				
				while ($queryListRows->fetch() ) {
					
					$rowFields = array(
						'ID'       => $ID,
						'username' => $username,
						'password' => $password,
						);
					
					$rowArray[] = $rowFields;
				}

				return $rowArray;
			} else {

				return $rowList;
			}
		}

		/**
		 * [Check if user exist]
		 * @param  [boolean]	$validatePassword  [validate password]
		 * @param  [string]		$requestedUsername [username to check for]
		 * @param  [string] 	$requestedPassword [password to check for]
		 * @return [boolean]
		 */
		public function validateUser($validatePassword, $requestedUsername, $requestedPassword){

			$userList = $this->listRows();

			$validUser = false;
			foreach ($userList as $value) {

				if($validatePassword == false){
					
					if($value['username'] == $requestedUsername){

						$validUser = true;
					}
				} else {
					
					if($value['username'] == $requestedUsername && $value['password'] == $requestedPassword){

						$validUser = true;
					}
				}
			}

			return $validUser;
		}
	}
?>