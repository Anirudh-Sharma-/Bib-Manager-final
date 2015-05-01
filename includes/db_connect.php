<?php

$dbhost = "isedbserver.cloudapp.net";
$dbuser = "user3";
$dbpass = "iop123!";
$dbname = "user3";
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
//test if connection is succeeded
if(mysqli_connect_errno()){
	die("Database connection failed: " .
	     mysqli_connect_error() . 
		 " (" . mysqli_connect_errno() . ")"
		 );
}
?>
