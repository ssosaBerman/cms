<?php
	session_name('activeSession');
	session_id($_POST['sessionID']);
	
	session_start();

	if (ini_get("session.use_cookies")) {
		
		$params = session_get_cookie_params();

		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}
	
	session_destroy();
	$file = fopen('/var/www/html/session/users.txt', 'c+');
	$usersArray = fgets($file);
	$usersArray = json_decode($usersArray, true);
	fclose($file);

	foreach ($usersArray as $userKey => $userValue) {

		if ( $userValue['sessionID'] == $_POST['sessionID'] ) {

			unset($usersArray[$userKey]['sessionID']);

			$file = fopen('/var/www/html/session/users.txt', 'w+');

			fwrite($file, json_encode($usersArray));
			fclose($file);

			echo "logged out refresh";
		}
	}