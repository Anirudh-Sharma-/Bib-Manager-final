<?php  require_once("session.php"); ?>
<?php  require_once("includes/db_connect.php"); ?>
<?php  require_once("functions.php"); ?>
<?php  require_once("validation_functions.php"); ?>
<?php // require_once("PHPMailer-master/class.phpmailer.php"); ?>
<?php
if(isset($_POST['submit'])){
	
	$username = mysql_prep($_POST["username"]);
	$email = $_POST["email"];
	$hashed_password = password_encrypt($_POST["password"]);
	
	//server side validations
	$required_fields = array("username", "email", "password");
	validate_presences($required_fields);
	
	$fields_with_max_lenghts = array("username" => 30);
	validate_max_length($fields_with_max_lenghts);
	
	validate_username_exist($username);
	//validate_email_exist($email);
	
	if(!empty($errors)){
		$_SESSION["errors"] = $errors;
		redirect_to("index.php");
	}
	//end: server side validations
	
	//$message = " ";
	//$message_default_libs = " ";
	
	
	//creating values for sending verification mail 
	//$headers = "MIME-Version: 1.0" . "\r\n";
	//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	//$headers .= 'From: <19.anirudh.sharma@gmail.com>' . "\r\n";	

	
	
	
	
	
	
	//inserting registration data
	
	$query = "INSERT INTO user_register (";
	$query .= " user_name, user_password, user_email, status, token";
	$query .= ") VALUES (";
	$query .= " '{$username}', '{$hashed_password}', '{$email}', 'FALSE', '{$token}'";
	$query .= ")";
	//echo ($query);
	$result = mysqli_query($connection, $query);
	if(isset($result)){
		//$message = "registration done";
		//echo ($message);
		$_SESSION["reg_message"] = "Registration done successfully.";
		
		//creating two default libraries: "Trash" and "Unfiled"
		
		$query_default_libs = "INSERT INTO library (";
		$query_default_libs .= " lib_display_name, lib_owner_email";
		$query_default_libs .= ") VALUES (";
		$query_default_libs .= " 'Trash', '{$email}'";
		$query_default_libs .= "),(";
		$query_default_libs .= " 'Unfiled', '{$email}'";
		$query_default_libs .= ")";
		//echo ($query_default_libs);
		$result_default_libs = mysqli_query($connection, $query_default_libs);
		
		if(isset($result_default_libs)){
			//$message_default_libs = "libraries created";
			//echo ($message_default_libs);
			//$_SESSION["message"] = "Libraries created successfully.";
		}else{
			//$message_default_libs = "libraries created";
			//echo ($message_default_libs);
			//$_SESSION["message"] = "Libraries cannot be created.";
		}   // ends: creating two default libraries: "Trash" and "Unfiled"
		//echo $message;
		//if(!mail($to,$subject,$message,$headers)){echo "failed";}//sending verification mail
		//*********************************************
		 
			$to = $email;
			//echo $to;
	
	$subject = "Bibliography Manager: Verification Account";
	 
	$token = md5($email.time());
	
	$txt = "Click this is verification code to verify your registration: <br>";
	$txt .= "<a href='verify.php?token=$token'>Click here</a>";
	
	    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // More headers
    $headers .= 'From: <19.anirudh.sharma@gmail.com>' . "\r\n";
	echo $txt;
	
	//mail($to,$subject,$txt,$headers)
		
	
	
	
	//function send_mail($to, $subject, $txt){
   // $to = $to;
//	echo $to;
 //   $subject = $subject;
 //   $txt = $txt;
    // Always set content-type when sending HTML email
  //  $headers = "MIME-Version: 1.0" . "\r\n";
  //  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // More headers
 //   $headers .= 'From: 19.anirudh.sharma@gmail.com' . "\r\n";
    
 //    echo "kuch to hua hai";
//	 mail($to,$subject,$txt,$headers);
//	 echo "sgsgsgsgs";
   // if(mail($to,$subject,$txt,$headers)){
	//	echo "Sjna an milo";
  //      global $mailSuccess;
  //      $mailSuccess = 1;
 //       $_COOKIE['message'] = $mailSuccess;
 //   }else{
 //       global $mailFailure;
 //       $mailFailure = 1;
 //       $_COOKIE['message'] = $mailFailure;
  //  }
//	}
	//$params = array("address"=>"$address", "text"=>"$text", "subject"=>"$subject");
//	echo "before";
//	send_mail($to, $subject, $txt);
//	echo "after";

		
	}else{
		//$message = "registration failed";
		//echo ($message);
		$_SESSION["reg_message"] = "Registration failed";
	}
	
	
	
	
	
	
}else{
	//if the POST request was not proper
} //end: if(isset($_POST['submit'])) 
?>
<html>
<head>
<title>Bibliography Registration</title>
<link rel="stylesheet" type="text/css" href="css/public.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="javascript/public.js"></script>
</head>
<body class="register">

<?php //$message = " "; ?>
<?php //$message_default_libs = " "; ?>
<div id="register-form">
<h1>Bibliography Manager</h1>

<?php echo message(); ?>
<?php echo reg_message(); ?>
<?php $errors = errors(); ?>
<?php echo form_errors($errors); ?>


<form name="register" action="index.php" onsubmit="return validateForm()" method="post" >
<table cellpadding="0" cellspacing="3" border="0">
 <tr><td class="label">Name</td><td><input type="text" name="username" /></td></tr>
 <tr><td class="label">Email</td><td><input type="email" name="email" required /></td></tr>
 <tr><td class="label">Password</td><td><input type="password" name="password" /></td></tr>
 <tr><td class="label"><input type="submit" name="submit" value="Register"></td><td class="login"><a href="login.php">LogIn</a></td></tr>
</table>
On registration a validation link will be sent to you email to validate your account.
</form>
</div>


</body>
</html>
<?php include("includes/db_close.php") ?>
