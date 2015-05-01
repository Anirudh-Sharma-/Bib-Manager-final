<?php  require_once("session.php"); ?>
<?php  require_once("functions.php"); ?>
<?php 
		$_SESSION["user_id"] = null;
		$_SESSION["user_name"] = null;
		$_SESSION["user_email"] = null;
		redirect_to("login.php");


?>