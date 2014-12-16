<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include($_SERVER['DOCUMENT_ROOT'] . '/phpCMS/library/library.php');

$userLogout = new User();
$destroySession = $userLogout->destroySession( $_COOKIE['sessionID'] );

if ( $destroySession == TRUE ) {
	
	echo "ok";
} else {
	echo 'error';
}

?>