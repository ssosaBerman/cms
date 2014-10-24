<?php 
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);

	include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');

	$allUsers = new User();
	
	echo json_encode( $allUsers->listRows() );
?>