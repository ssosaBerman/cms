<?php 
	include 'gump.php';

	$validated = GUMP::is_valid($_POST, array(
		'username' => 'valid_email',
		'password' => 'max_len,100|min_len,6',
		));

	if($validated === true) {

		echo "ok";
	} else {
		echo "<pre>";
		print_r($validated);
		echo "</pre>";
	}
?>