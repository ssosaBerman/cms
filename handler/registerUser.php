<?php 
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);

	include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');

	$newUser = new User();

	$newUserCreate = $newUser->create($_POST['username'], $_POST['password']);

	if ( is_array($newUserCreate) ) {

		// return error array
		echo json_encode($newUserCreate);
	} elseif ( is_numeric($newUserCreate) == true ) {

		// return new user ID
		echo $newUserCreate;
	} else {
		
		echo json_encode('User Exist');		
	}
?>