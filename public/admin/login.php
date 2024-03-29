<?php
require_once("../../includes/initialize.php");

if($session->is_logged_in()) {
	redirect_to("index.php");
}

// Remember to give your form's submit tag a name="submit" attribute
if(isset($_POST['submit'])) { // form has been submited
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	// Check database to see if username/password exist.
	$found_user = User::authenticate($username, $password);

	if($found_user) {
		$session->login($found_user);
		log_action('Login', "{$found_user->username} logged in.");
		redirect_to("index.php"); 
	} else {
		// username/password combo was not found in the database
		$message = "Username/password combination incorrect";
	}
} else { // Form has not been submitted
	$username = "";
	$password = "";
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Photo Gallery</title>
	<link rel="stylesheet" href="../stylesheets/main.css">
</head>
<body>
	<div id="header">
		<h1>Photo Gallery</h1>
	</div>
	<div id="main">
		<h2>Staff Login</h2>
		<?php if(isset($message)): ?>
			<?=output_message($message);?>
		<?php endif; ?>
		<form action="login.php" method="post">
			<table>
				<tr>
					<td>Username:</td>
					<td><input type="text" name="username" maxlength="30" value="<?=htmlentities($username)?>"></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type="password" name="password" maxlength="30" value="<?=htmlentities($password)?>"></td>
				</tr>
				<tr>
					<td colspan="2">
					<input type="submit" name="submit" value="Login">
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div id="footer">Copyright <?=date("Y", time());?>, Justin Page</div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>