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
			// $connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

			// return $connection;
			
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

		// install user table, if problem it will return error
		public function install(){

			$queryCreateUserTable = "CREATE TABLE users(ID Integer PRIMARY KEY NOT NULL AUTO_INCREMENT, username CHAR(99), password CHAR(99))";
			
			$connection =  $this->databaseConnect();

			if ( $connection->query($queryCreateUserTable) === true ) {
				
				return true;
			} else {

				return $connection->error;
			}

			/*if ( mysqli_query($connection, $queryCreateUserTable) ) {
				
				return true;
			} else {

				return mysqli_error($connection);
			}*/
		}

		public function uninstall(){
			
			$unInstalTable = "DROP TABLE users";
			
			$connection = $this->databaseConnect();

			if ( $connection->query($unInstalTable) === true ) {
				
				return true;
			} else {
				
				return $connection->error;
			}

			/*if( mysqli_query($connection, $uninstaltable) ){
				
				return true;
			} else {
			
				return mysqli_error($connection);
			}*/
		}

		public function create( $requestedUsername, $requestedPassword ){
			
			$connection = $this->databaseConnect();
		
			$queryAddUser = "INSERT INTO users(username, password) VALUES('$requestedUsername', '$requestedPassword');";

			if( $connection->query($queryAddUser) === true ) {

				$this->ID = $connection->insert_id;
				$this->username = $requestedUsername;
				$this->password = $requestedPassword;

				return $this->ID;
			} else {

				return $connection->error;
			}

			/*if(mysqli_query($connection, $queryAddUser)){
				
				$this->ID = mysqli_insert_id($connection);
				$this->username = $requestedUsername;
				$this->password = $requestedPassword;
				
				return $this->ID;
			} else {
				
				return mysqli_error($connection);
			}*/
		}

		public function read(){
			
			$data = array(
				'username' => $this->username,
				'password' => $this->password,
				'ID' => $this->ID,
			);
			
			return $data;
		}

		public function update($findID ,$newName, $newPassword){

			$connection = $this->databaseConnect();

			$queryUpdateUser = "UPDATE `users` SET `username` = '$newName', `password` = '$newPassword' WHERE ID = $findID;";

			if ( $connection->query($queryUpdateUser) === true ){

				return true;
			} else {

				return $connection->error;
			}

			/*if ( mysqli_query($connection, $queryUpdateUser) ) {
				
				$this->username = $newName;
				$this->password = $newPassword;
				
				return true;
			} else {

				return mysqli_error($connection);
			}*/
		}

		public function destroy($deleteID){
			
			$connection = $this->databaseConnect();

			$queryDeleteUser = "DELETE FROM `users` WHERE ID = $deleteID;";

			if ( $connection->query($queryDeleteUser) === true ) {
				
				unset($this->username);
				unset($this->password);
				unset($this->ID);
				
				return true;
			} else {

				return $connection->error;
			}

			/*if (mysqli_query($connection, $queryDeleteUser)) {
				
				unset($this->username);
				unset($this->password);
				unset($this->ID);
				
				return true;
			} else {
				
				return mysqli_error($connection);
			}*/
		}
		
		public function listRows(){
			
			$connection = $this->databaseConnect();

			$queryListRows = "SELECT * FROM users;";

			$rowList = $connection->query($queryListRows);
			
			if( $rowList ) {

				$rowArray = array();

				while ($row = $rowList->fetch_array(MYSQLI_ASSOC) ) {
					
					array_push($rowArray, $row);
				}

				return $rowArray;
			} else {

				return $rowList;	
			}
			
			/*$rowList = mysqli_query($connection, $queryListRows);

			if ( $rowList ) {

				$rowArray = array();
				
				while ($row = mysqli_fetch_array($rowList, MYSQLI_ASSOC)) {
					
					array_push($rowArray, $row);
				}
				
				return $rowArray;
			} else {

				return $rowList;
			}*/
		}
	}
?>