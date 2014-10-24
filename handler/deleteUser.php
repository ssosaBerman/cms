<?php 
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);

	include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');

	$deleteuser = new User();
	
	$deleteUser = $deleteuser->destroy($_POST['deleteID']);
	
	if ( $deleteUser !== true ) {
		
		echo $deleteUser;
	} else {

		echo "success";
	}	
?>