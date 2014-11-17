<?php

include 'passwordClass.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="/js/libraries/jquery/jquery.min.js"></script>
	<script>
	var debug0;
	$(document).ready(function(){

		$('.passwordForm').on('submit', function(e){
			e.preventDefault();
			hashVal = $('.test').text().trim();
			passwordVal = $(this).find('.password').val()
			
			$.post('encrypt.php', { password: passwordVal, hash: hashVal}, function(response){			
				$('.feedback').html(response)
			})
		})
	})
	</script>
</head>
<body>
	<form action="encrypt.php" method="post" class="passwordForm">
		<label for="password">password</label>
		<input type="text" name="password" class="password" autofocus/>
		<input type="submit">
	</form>
	<br />
	<br />
	<div class="test">
		<?php print_r( makePassword() ); ?>	
		
	</div>
	<div class="feedback"></div>
</body>
</html>