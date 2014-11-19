<?php 
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);

	include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');

	$newUser = new User();

	$newUserCreate = $newUser->create($_POST['username'], $_POST['password']);

	if ( is_array($newUserCreate) || is_numeric($newUserCreate) == true ) {

		// return error array
		echo json_encode($newUserCreate);
	} else {
		
		echo json_encode('User Exist');		
	}
?>