<!DOCTYPE html>
<html>
<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
		
	$activeSession = ( isset($_COOKIE['sessionID']) );

	if ( $activeSession ) {	

		$userData = file_get_contents('/var/www/html/session/users.txt');
		$usersArray = json_decode($userData, true);
		
		foreach ($usersArray as $userInfo) {

			if ( isset($userInfo['sessionID']) ) {
				 
				if ( $userInfo['sessionID'] == $_COOKIE['sessionID'] ) {
					
					session_name('activeSession');
					session_id($_COOKIE['sessionID']);
					session_start();
					$usersArray = $userInfo;
				}
			} 
		}
	}
?>
<head>
	<script src="/js/libraries/jquery/jquery.min.js"></script>
	<script src="/js/libraries/jquery.cookie/jquery.cookie.js"></script>
	<script>
		$(document).ready(function(){

			$('form').on('submit', function (e) {
				e.preventDefault();

				formObj = $(this);
				
				postobj = {
					username 	: formObj.find('.username').val(),
					password 	: formObj.find('.password').val(),
				};
				
				$.post(formObj.attr('action'), postobj, function(data){

					formattedData = $.parseJSON(data)

					if ( typeof formattedData == 'object' ) {
						
						$('.feedback').html(objectToText(formattedData));

						if ( $('.rememberMe:checked').size() == 1 ) {

							$.cookie('sessionID', $.cookie('activeSession'), {expires: 7});
						} else {

							$.cookie('sessionID', $.cookie('activeSession'));
						};

						formObj.remove();
					} else {

						$('.feedback').html(formattedData);
					};
				})
			})

			$('.clear').on('click', function(e){
				e.preventDefault();

				$.post('sessionStop.php', { sessionID: $.cookie('sessionID') }, function(response){
					
					$.removeCookie('sessionID');
					// $.removeCookie('activeSession');

					// var parseResponse = $.parseJSON(response)

					$('.feedback').html(response);
				})
			})
		})

		function objectToText (theObject) {

			return "<pre>" + JSON.stringify(theObject, null, 4) + "</pre>";
		}
	</script>
</head>
<body>
	<?php if ( $activeSession ): ?>
	<input type="button" class="clear" value="log out">
	<br />
	<pre>
	<?php print_r($usersArray); ?>
	</pre>
	
	<?php else: ?>
	
	<form action="sessionStart.php" method="post">
		<label for="username">username</label>
		<input type="text" name="username" class="username"/>
		<br />
		<label for="username">password</label>
		<input type="password" name="password" class="password"/>
		<br />
		<span>Remember Me</span>
		<input type="checkbox" class='rememberMe' />
		<br />
		<input type="submit" />
	</form>
	
	<?php endif ?>

	<div class="feedback"></div>
</body>
</html>