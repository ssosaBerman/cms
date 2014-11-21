<?php
	ini_set('display_errors', 1);	
	error_reporting(E_ALL);
?>
<html>
	<head>
		<style>
			*{
				font-family: Arial, Helvetica, Sans-Serif;
			}

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
		<div class="currentUser">
			<form action="" class="formLogout">
				Not logged-in
			</form>
		</div>
		<div class="usersTableInstaller">
			<h3>Install / Uninstall table</h3>
			<form action="/handler/install.php" class="formInstall">
				<input type="submit" value="Install" />
			</form>
			<form action="/handler/uninstall.php" class="formUninstall">
				<input type="submit" value="Uninstall" />
			</form>
			<div class="feedback"></div>
		</div>
		<div class="registerUser">
			<h3>Register</h3>
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
			<h3>Update</h3>
			<form action="/handler/updateUser.php" class="formUpdateUser">
				
				<select name="userList" class="userList">
					  <option value="" disabled selected>Pick User</option>
				</select>
				
				<label for="password">New Password</label>
				<input type="password" name="newPassword" class="newPassword" />
				
				<input type="submit" value="Update" />
			</form>
			<div class="feedback"></div>
		</div>
		<div class="deleteUser">
			<h3>Delete</h3>
			<form action="/handler/deleteUser.php" class="formDeleteUser">
				<select name="userList" class="userList">
					  <option value="" disabled selected>Pick User</option>
				</select>
				
				<input type="submit" value="Delete" />
			</form>
			<div class="feedback"></div>
		</div>
		<div class="loginUser">
			<h3>Login</h3>
			<form action="/handler/userLogin.php" class="formloginUser">
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