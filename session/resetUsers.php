<?php
$users = array(
	array(
		'id' => 0,
		'username' => 'test',
		'password' => '123',
	),
	array(
		'id' => 1,
		'username' => 'test2',
		'password' => '123',
	),
		
);

$file = fopen('/var/www/html/session/users.txt', 'c+');

$arrayText = json_encode($users);

fwrite($file, $arrayText);

fclose($file);

?>