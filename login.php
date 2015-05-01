<?php  require_once("session.php"); ?>
<?php  require_once("includes/db_connect.php"); ?>
<?php  require_once("functions.php"); ?>
<?php  require_once("validation_functions.php"); ?>
<?php
$username = "";
if(isset($_POST['submit'])){
	//process the form for user authentication
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	$found_user = attempt_login($username, $password);
	
	if($found_user){
		//Success, mark user as logged in 
		$_SESSION["user_id"] = $found_user["user_id"];
		$_SESSION["user_name"] = $found_user["user_name"];
		$_SESSION["user_email"] = $found_user["user_email"];
		redirect_to("home.php");
	}else{
		$_SESSION["message"] = "Username/Password not found";
	}
	
	
} else{
	
}//end: process the form for user authentication
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/public.css">
</head>
<body class="register">
<div id="register-form">
<h1>Bibliography Manager</h1>
<?php echo message(); ?>
<?php $errors = errors(); ?>
<?php echo form_errors($errors); ?>
<form action="login.php" method="post">
<table cellpadding="0" cellspacing="3" border="0">
 <tr><td class="label">Username</td><td><input type="text" name="username" value="<?php echo htmlentities($username); ?>" /></td></tr>
 <tr><td class="label">Password</td><td><input type="password" name="password" /></td></tr>
 <tr><td class="label"><input type="submit" name="submit" value="LogIn"></td><td class="login"><a href="index.php">Register</a></td></tr>
 &nbsp;
 
</form>
</div>
</body>
</html>