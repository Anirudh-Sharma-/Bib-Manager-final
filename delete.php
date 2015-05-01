<?php  require_once("session.php"); ?>
<?php  require_once("includes/db_connect.php"); ?>
<?php  require_once("functions.php"); ?>
<?php  require_once("validation_functions.php"); ?>

<?php
// while deleting library with references
// 1: move references to selected library
//2: delete library and redirect to home.php
if(isset($_POST["final_del_library_submit"])){
	$lib_id_to_be_deleted = $_SESSION["del_lib_id"];
	$user_email = $_SESSION["user_email"];
	$selected_lib_id = $_POST["lib_to_move"];
	echo ($lib_id_to_be_deleted);
	//echo ($user_email);
	echo "<br>";
	echo ($selected_lib_id);
	$result_ref_ids = find_all_reference_by_lib_id($lib_id_to_be_deleted);
	$ref_ids = array();
	while($row = mysqli_fetch_assoc($result_ref_ids)){
			$ref_ids[] = $row["ref_id"];
	}
	
	move_references_to_selected_library($ref_ids, $selected_lib_id);
	delete_library_by_id($lib_id_to_be_deleted);
	redirect_to("home.php");
}else{
	//this is probably the GET request
}
?>

<?php 
//while deleting library with no references
if(isset($_POST["final_del_emp_library_submit"])){
	
	$lib_id_to_be_deleted = $_SESSION["del_lib_id"];
	delete_library_by_id($lib_id_to_be_deleted);
	redirect_to("home.php");
}else{
	//this is probably the GET request
}

?>


<html>
<head>
<script src="javascript/public.js"></script>
</head>
<body>
<?php
if($_SESSION["library_has_reference"]){ ?>
	<!-- code for listing remaining libraries to move references to -->
		<form action="delete.php" method="post">
		<fieldset>
		<legend>Select library in which you want your references moved to:</legend>
		<?php $result_lib_listing_for_deletion = find_libraries_for_moving_references($_SESSION["del_lib_id"], $_SESSION["user_email"]); ?>
		<?php
			while($row = mysqli_fetch_assoc($result_lib_listing_for_deletion)){
				echo "<input type=\"radio\" name=\"lib_to_move\" value=\"{$row["lib_id"]}\">{$row["lib_display_name"]}<br>";
				
			}
		?>
		<input type="submit" name="final_del_library_submit" value="Move and Delete"><br>
		
		
		</fieldset>
		</form>
	<a href="home.php">Cancel</a>
	
<?php } else{ ?>
	
	 <h5>You sure you want to delete this library </h5><br>
	<form action="delete.php" method="post">
	<input type="submit" name="final_del_emp_library_submit" value="Delete">
	</form> 
	
<?php }
?>



</body>
</html>