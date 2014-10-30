<?php 
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);

	include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');

	$validateUser = new User();

	$validUser = $validateUser->validateUser(true, $_POST['username'], $_POST['password']);

	if( $validUser == true ) {

		echo 'OK';
	} else {

		echo 'Not found';
	}
?>