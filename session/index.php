<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	if ( isset($_COOKIE['active_user']) ) {
		
		session_name('current_user');
		session_id($_COOKIE['active_user']);
		session_start();
	}
	// if ( file_exists('/var/www/html/session/current_user.txt') ) {

	// 	$file = fopen('/var/www/html/session/current_user.txt', 'c+');
	// 	$savedSession = fgets($file);
	// 	fclose($file);

	// 	if ( $savedSession == $_COOKIE['active_user']) {
			
	// 		session_name('current_user');
	// 		session_id($savedSession);
	// 		session_start();
	// 	}
	// }
?>

<!DOCTYPE html>
<html>
<head>
	<script src="/js/libraries/jquery/jquery.min.js"></script>
	<script>
	$(document).ready(function(){
		$('form').on('submit', function (e) {
			e.preventDefault();

			formObj = $(this);
			
			postobj = {
				username : formObj.find('.username').val(),
				password : formObj.find('.password').val()
			};
			
			$.post(formObj.attr('action'), postobj, function(data){

				formattedData = $.parseJSON(data)
				
				$('.feedback').html(objectToText(formattedData))

				formObj.remove();
			})
		})
	})

	function objectToText (theObject) {

		return "<pre>" + JSON.stringify(theObject, null, 4) + "</pre>";
	}
	</script>
</head>
<body>
	<?php 
	if (isset($_SESSION)): ?>
	
	<pre>
	<?php 
		print_r($_SESSION);
		print_r($_COOKIE);
	?>
	</pre>
	
	<?php else: ?>
	
	<form action="demo_session1.php" method="post">
		<label for="username">username</label>
		<input type="text" name="username" class="username"/>
		<br>
		<label for="username">password</label>
		<input type="password" name="password" class="password"/>
		<br>
		<input type="submit" />
	</form>
	
	<?php endif ?>
	<div class="feedback"></div>
</body>
</html>