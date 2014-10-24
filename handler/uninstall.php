<?php
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);
	
	include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');
				
	$userObject = new user();
	
	$newUserUninstall = $userObject->uninstall();

	if( $newUserUninstall !== true ){
	
		echo $newUserUninstall;
	} else {
	
		echo 'success';
	}
?>