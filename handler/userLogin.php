<?php 
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);

	include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');

	$validateUser = new User();

	$validUser = $validateUser->validateUser(TRUE, $_POST['username'], $_POST['password']);

	if( is_array($validUser) == FALSE && $validUser ) {
		
		$sessionID = $validateUser->setSession($_POST['username']);

		if ( $sessionID !== FALSE ) {
			
			echo $sessionID;
		}
	} else {

		echo 'Not found';
	}
?>