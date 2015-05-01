<?php  require_once("session.php"); ?>
<?php  require_once("includes/db_connect.php"); ?>
<?php  require_once("functions.php"); ?>
<?php  require_once("validation_functions.php"); ?>
<?php confirm_logged_in(); ?>

		<?php
			// getting user information
		$user_id = $_SESSION["user_id"]; 
		$username = $_SESSION["user_name"]; 
		$user_email = $_SESSION["user_email"];
		?>
<?php
$details_change = false;
//start: change registration details_submit
if(isset($_POST["details_submit"])){
	$new_username = $_POST["new_username"];
	 
	$new_email = $_POST["new_email"];
	$new_password = $_POST["new_password"];
	
	//checking for new username
	if($new_username != " "){
		
$details_change = true;
		//make query for new username
		change_user_name($user_id, $new_username);	
	}
	
	//checking for new password
	if($new_password != " "){
		//encrypt the password
		//make query for changing password
		$details_change = true;
		$new_hashed_password = password_encrypt($new_password);
		change_user_password($user_id, $new_hashed_password);
	}
	
	//checking fof the email change
	if($new_email != " "){
		//change the email
		//set the account to FALSE status
		//send the verification tools
		$details_change = true;
		change_user_email($user_id, $new_email);
		change_user_email_in_shared_library($user_email, $new_email);
		//change_acount_status_to_inactive($user_id);
		

		
		
		
		
	}
	
	
	
}else{
	//this is probably the GET request
}


 // end: change registration details_submit
?>



<!DOCTYPE html>
<html>
<head>
<title>Edit Details</title>
<link rel="stylesheet" type="text/css" href="css/public.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="javascript/public.js"></script>
<script type="text/javascript" src="javascript/sorttable.js"></script>
</head>

<body>
<div id="wrapper">
		<div id="header">
		<h1>Bibliograhy Manager</h1><br>
		<?php echo message(); ?>
		<?php $errors = errors(); ?>
		<?php echo form_errors($errors); ?>
		<div id="user_info"> 
		<?php $output = "Welcome: ";
				$output .= $_SESSION["user_name"];
				echo ($output);
		?>
		</div>
		<div id="logout">
		<a href="logout.php">LogOut</a>
		
		</div>
		</div>
		<div id="account_info">

		<table cellpadding="0" cellspacing="3" border="0" align="centre" margin-left= "auto" margin-right= "auto">
    
		<form action="index.php" method="post">
		<tr><td>Current Username:</td><td> <?php echo $username;?></td></tr>
		<tr><td>New Username:</td><td>  <input type="text" name="new_username" value=" " /></td></tr>
		<tr><td>Current Email:</td><td>  <?php echo $user_email;?></td></tr>
		<tr><td>New Email:</td><td>  <input type="email" name="new_email" value=" " /></td></tr>
		<tr><td> New Password:</td><td>  <input type="password" name="new_password" value=""/></td></tr>
		<tr><td><input type="submit" name="details_submit" value="Save Changes"></td><td> <a href="home.php">Cancel</a></td></tr>
		
		
		</form>
		<table>
		</div>
		
		
				<div id="last_footer">
		Copyright © BibManager
		</div>
</div>


</body>


</html>