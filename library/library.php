<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	class installer{

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

		public function create( $requestedUsername, $requestedPassword ){
			
			$connection = $this->databaseConnect();
		
			$queryAddUser = "INSERT INTO users(username, password) VALUES('$requestedUsername', '$requestedPassword');";
			
			$userAdded = $connection->query($queryAddUser);
			
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

		public function getUser($searchUsername, $searchPassword){

			$connection = $this->databaseConnect();

			$queryMatchedUser = "SELECT * FROM `users` WHERE `username` = '$searchUsername' AND `password` = '$searchPassword';";

			$getUserRow = $connection->query($queryMatchedUser);

			$userFound = $getUserRow->num_rows;

			if ( $getUserRow ) {

				return $userFound;
			} else {

				return $connection->error;
			}
		}
	}
?>