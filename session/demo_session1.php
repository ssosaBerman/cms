<?php 
	session_name('current_user');
	session_start();
			
	// session variables
	$_SESSION['name'] = $_POST['username'];
	$_SESSION['password'] = $_POST['password'];

	// $file = fopen('/var/www/html/session/current_user.txt', 'c+');
	// fwrite($file, session_id());
	// fclose($file);

	setcookie('active_user', session_id(), time()+36000);
	echo json_encode($_SESSION);
?>