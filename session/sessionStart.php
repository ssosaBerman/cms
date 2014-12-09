<?php 
	session_name('activeSession');
	session_start();

	$file = fopen('/var/www/html/session/users.txt', 'c+');
	$usersArray = fgets($file);
	$usersArray = json_decode($usersArray, true);
	fclose($file);

	foreach ($usersArray as $userKey => $usersInfo) {
		
		if ( $usersInfo['username'] == $_POST['username'] ) {
			
			// $_SESSION['user'] = $usersInfo['id'];
			
			$usersInfo['sessionID'] = session_id();
			
			$usersArray[$userKey]['sessionID'] = session_id();
			
			$file = fopen('/var/www/html/session/users.txt', 'w+');
			fwrite($file, json_encode($usersArray));
			fclose($file);

			echo json_encode($usersInfo);
			die();
		}
	}
	echo json_encode('invalid user');
?>