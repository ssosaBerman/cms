<?php
	ini_set('display_errors', 1);
	
	error_reporting(E_ALL);
?>
<html>
	<head>
	<style>
		input, select{
			display: block;
		}
		div{
			border: 1px solid #000;
			margin-bottom: 5px;
		}
	</style>
		<script src="/js/libraries/jquery/jquery.min.js"></script>
		<script src="/js/script.js"></script>
	</head>
	<body>
		<div class="usersTableInstaller">
			<h2>Install / Uninstall table</h2>
			<form action="/handler/install.php" class="formInstall">
				<input type="submit" value="Install" />
			</form>
			<form action="/handler/uninstall.php" class="formUninstall">
				<input type="submit" value="Uninstall" />
			</form>
			<div class="feedback"></div>
		</div>
		<div class="registerUser">
			<h2>Register</h2>
			<form action="/handler/registerUser.php" class="formRegisterUser">
				<label for="username">Username</label>
				<input type="text" name="username" class="username" />
				
				<label for="password">Password</label>
				<input type="password" name="password" class="password" />
				
				<input type="submit" value="Register" />
			</form>
			<div class="feedback"></div>
		</div>
		<div class="updateUser">
			<h2>Update</h2>
			<form action="/handler/updateUser.php" class="formUpdateUser">
				
				<select name="userList" class="userList">
					  <option value="noUser" disabled selected>Pick User</option>
				</select>
				
				<label for="username">New username</label>
				<input type="text" name="newUsername" class="newUsername" />
				
				<label for="password">New Password</label>
				<input type="password" name="newPassword" class="newPassword" />
				
				<input type="submit" value="Update" />
			</form>
			<div class="feedback"></div>
		</div>
		<div class="deleteUser">
			<h2>Delete</h2>
			<form action="/handler/deleteUser.php" class="formDeleteUser">
				<select name="userList" class="userList">
					  <option value="noUser" disabled selected>Pick User</option>
				</select>
				
				<input type="submit" value="Delete" />
			</form>
			<div class="feedback"></div>
		</div>
		<div class="loginUser">
			<h2>Login</h2>
			<form action="/handler/validateUser.php" class="formloginUser">
				<label for="username">Username</label>
				<input type="text" name="username" class="username" />
				
				<label for="password">Password</label>
				<input type="password" name="password" class="password" />
				
				<input type="submit" value="Login" />
			</form>
			<div class="feedback"></div>
		</div>
	</body>
</html>