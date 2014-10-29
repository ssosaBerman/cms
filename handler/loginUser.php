<?php 
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);

	include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');

	$findUser = new User();

	$getUser = $findUser->getUser($_POST['username'], $_POST['password']);

	if ( $getUser > 0) {
		
		echo 'OK';
	} else {

		echo 'Username or Password don\'t matched';
	}
?>