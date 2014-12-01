<?php
$handle = fopen('/var/www/html/session/current_user.txt', 'c+');
$cookie = fgets($handle);
fclose($handle);

session_name('current_user');
session_id($cookie);
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<pre>
<?php print_r($_SESSION); ?>
</pre>

</body>
</html>