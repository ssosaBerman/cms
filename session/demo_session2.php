<?php
session_name('current_user')
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<pre>
<?php print_r($_SESSION); ?>
</pre>
<?php
$_SESSION["favcolor"] = "yellow";
?>
<pre>
<?php print_r($_SESSION); ?>
<?php session_abort() ?>
</pre>
</body>
</html>