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
	}

	class user extends installer{
		private $username;
		private $password;
		private $ID;

		// Create table users
		public function install(){

			$queryCreateUserTable = "CREATE TABLE users(ID Integer PRIMARY KEY NOT NULL AUTO_INCREMENT, username CHAR(99), password CHAR(99))";
			
			$connection =  $this->databaseConnect();

			$tableUsersCreated = $connection->query($queryCreateUserTable);

			if ( $tableUsersCreated === true ) {
				
				return true;
			} else {

				return $connection->error;
			}
		}

		//Delete user table
		public function uninstall(){
			
			$queryUnInstalTable = "DROP TABLE users";
			
			$connection = $this->databaseConnect();

			$tableUsersDeleted = $connection->query($queryUnInstalTable);

			if ( $tableUsersDeleted === true ) {
				
				return true;
			} else {
				
				return $connection->error;
			}
		}

		// Create new user with requested username and password
		public function create($requestedUsername, $requestedPassword){
			
			$connection = $this->databaseConnect();
		
			$queryAddUser = "INSERT INTO users(username, password) VALUES('$requestedUsername', '$requestedPassword');";
			
			$userExist = $this->validateUser(false, $requestedUsername, $requestedPassword);

			$userAdded = ( $userExist == false )? $connection->query($queryAddUser) : false;
			
			if( $userAdded === true ) {

				$this->ID = $connection->insert_id;
				$this->username = $requestedUsername;
				$this->password = $requestedPassword;

				return $this->ID;
			} else {

				return $connection->error;
			}
		}

		public function read(){
			
			$userData = array(
				'username' => $this->username,
				'password' => $this->password,
				'ID' => $this->ID,
			);
			
			return $userData;
		}

		public function update($userID ,$newName, $newPassword){

			$connection = $this->databaseConnect();

			$queryUpdateUser = "UPDATE `users` SET `username` = '$newName', `password` = '$newPassword' WHERE ID = $userID;";

			$userUpdated = $connection->query($queryUpdateUser);

			if ( $userUpdated === true ){

				return true;
			} else {

				return $connection->error;
			}
		}

		public function destroy($userID){
			
			$connection = $this->databaseConnect();

			$queryDeleteUser = "DELETE FROM `users` WHERE ID = $userID;";

			$userDeleted = $connection->query($queryDeleteUser);

			if ( $userDeleted === true ) {
				
				unset($this->username);
				unset($this->password);
				unset($this->ID);
				
				return true;
			} else {

				return $connection->error;
			}
		}
		
		public function listRows(){
			
			$connection = $this->databaseConnect();

			$queryListRows = "SELECT * FROM users;";

			$rowList = $connection->query($queryListRows);
			
			if( $rowList ) {

				$rowArray = array();
				
				while ($row = $rowList->fetch_array(MYSQLI_ASSOC) ) {
					
					$rowArray[] = $row;	
				}

				return $rowArray;
			} else {

				return $rowList;
			}
		}

		public function validateUser($validatePassword, $requestedUsername, $requestedPassword){

			$userList = $this->listRows();

			$validUser = false;

			foreach ($userList as $value) {
				
				if( $value['username'] == $requestedUsername && $value['password'] == $requestedPassword && $validatePassword == true) {

					$validUser = true;
				}else if ( $value['username'] == $requestedUsername && $validatePassword == false) {

					$validUser = true;
				}
			}

			return $validUser;
		}
	}
?>