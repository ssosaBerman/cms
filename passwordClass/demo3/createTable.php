<?php 
$db_host 	 = 'localhost';
$db_user 	 = 'root';
$db_password = 'sjsm123456';
$db_name 	 = 'myapp';

$connection = new mysqli($db_host, $db_user, $db_password, $db_name);
;
if ( $connection->query('CREATE table users (user varchar(60), pass varchar(60), unique (user));') == true ) {
	echo 'true';
} else {
	echo $connection->error;
}

 ?>