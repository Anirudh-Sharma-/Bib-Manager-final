<?php  require_once("session.php"); ?>
<?php  require_once("includes/db_connect.php"); ?>
<?php  require_once("functions.php"); ?>
<?php  require_once("validation_functions.php"); ?>

<?php
$registration_failed = false;
$user_token = $_GET["token"];

$query = "SELECT * ";
$query .= "FROM user_register ";
$query .= "WHERE token = '{$user_token}' ";
$query .= "LIMIT 1";
	$result = mysqli_query($connection, $query);
	
	confirm_query($result);
	if(mysqli_num_rows($result) == 1){
				//Success, mark user as logged in
		$row = mysqli_fetch_assoc($result);
		$_SESSION["user_id"] = $row["user_id"];
		$_SESSION["user_name"] = $row["user_name"];
		$_SESSION["user_email"] = $row["user_email"];
		$registration_failed = true;
		//query for changing account status to active
		$status_query = "UPDATE user_register SET status = 'TRUE' WHERE user_id = '{$row["user_id"]}'";
	$result_status = mysqli_query($connection, $status_query);
	confirm_query($result_status);		
		//make query for token deletion 
		redirect_to("home.php");
	}else{
		$registration_failed = false;
	}

 
?>



<html>
<head>
</head>
<body>
<?php
if(!$registration_failed){
	echo "Cannot Register";
}
?>
</body>
</html>






