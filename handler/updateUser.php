<?php 
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);

	include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');

	$updateUser = new User();
	
	$updateUserUpdater = $updateUser->update($_POST['userID'], $_POST['newUsername'], $_POST['newPassword']);
	
	if ( $updateUserUpdater !== true ) {
		
		echo $updateUserUpdater;
	} else {

		echo "success";
	}	
?>