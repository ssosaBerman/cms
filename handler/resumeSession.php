<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include($_SERVER['DOCUMENT_ROOT'] . '/library/library.php');

$activeSession = new User();
$activeUser = $activeSession->setSession();

if ( $activeUser !== FALSE ) {
	
	echo json_encode($activeUser);
} else {

	if ( $activeSession->destroySession($_COOKIE['sessionID']) ) {
		
		echo json_encode('Not logged-in');
	}
}
?>