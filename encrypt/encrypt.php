<?php
	include('passwordClass.php');
	
	if(isset($_POST['password'])){
		print_r( makePassword($_POST['password']) );
		die();
	}
?>