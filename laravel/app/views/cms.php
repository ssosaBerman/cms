<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>laravel</title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			margin:0;
			font-family:'Lato', sans-serif;
			text-align:left;
			color: #999;
		}

		.welcome {
			width: 300px;
			height: 200px;
			position: absolute;
			left: 50%;
			top: 50%;
			margin-left: -150px;
			margin-top: -100px;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 16px 0 0 0;
		}
	</style>
</head>
<body>
	<style>
	body{
		width: 50%;
		margin: 0 auto;
	}
	input, select{
		display: block;
	}

	div{
		border: 1px solid #000;
		margin-bottom: 1px;
		padding: 5px;
	}
	</style>
	<div class="currentUser">
		<form action="" class="formLogout">
			Not logged-in
		</form>
	</div>

	<?php echo $install ?>
	<?php echo $register ?>
<body/>
</html>