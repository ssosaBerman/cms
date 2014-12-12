<?php 
	session_name('activeSession');
	session_start();

	$userData = file_get_contents('/var/www/html/session/users.txt');
	$usersArray = json_decode($userData, true);

	foreach ($usersArray as $userKey => $usersInfo) {
		
		if ( $usersInfo['username'] == $_POST['username'] ) {
			
			$usersArray[$userKey]['sessionID'] = session_id();
			
			$file = fopen('/var/www/html/session/users.txt', 'w+');
			fwrite($file, json_encode($usersArray));
			fclose($file);

			echo json_encode($usersArray[$userKey]);
			die();
		}
	}
	echo json_encode('invalid user');
?>