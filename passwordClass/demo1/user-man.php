<?php

require '../PasswordHash.php';

// In a real application, these should be in a config file instead
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'sjsm123456';
$db_name = 'myapp';

// Base-2 logarithm of the iteration count used for password stretching
$hash_cost_log2 = 8;
// Do we require the hashes to be portable to older systems (less secure)?
$hash_portable = FALSE;

function fail($pub, $pvt = '')
{
	$msg = $pub;
	if ($pvt !== '')
		$msg .= ": $pvt";
	exit("An error occurred ($msg).\n");
}

header('Content-Type: text/plain');

$user = $_POST['user'];
$pass = $_POST['pass'];

// Should validate the username length and syntax here

$db = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
if (mysqli_connect_errno())
	fail('MySQL connect', mysqli_connect_error());

$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
$hash = $hasher->HashPassword($pass);
if (strlen($hash) < 20)
	fail('Failed to hash new password');
unset($hasher);

($stmt = $db->prepare('insert into users (user, pass) values (?, ?)'))
	|| fail('MySQL prepare', $db->error);
$stmt->bind_param('ss', $user, $hash)
	|| fail('MySQL bind_param', $db->error);
$stmt->execute()
	|| fail('MySQL execute', $db->error);

$stmt->close();
$db->close();

echo "User created\n";

?>
