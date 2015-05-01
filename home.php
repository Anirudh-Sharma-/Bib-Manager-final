<?php  require_once("session.php"); ?>
<?php  require_once("includes/db_connect.php"); ?>
<?php  require_once("functions.php"); ?>
<?php  require_once("validation_functions.php"); ?>
<?php confirm_logged_in(); ?>

<?php 
//for redirecting to 

?>

<?php 
//reference form submission
if(isset($_POST["ref_submit"])){
	
	//getting the id of unfiled lib
	// for the user who is logged in 
	$query_unfiled_lib_id = "SELECT * ";
	$query_unfiled_lib_id .= "FROM library ";
	$query_unfiled_lib_id .= "WHERE lib_owner_email='{$_SESSION["user_email"]}' ";
	$query_unfiled_lib_id .= "AND lib_display_name='Unfiled' ";
	$query_unfiled_lib_id .= "LIMIT 1";
	$lib_set = mysqli_query($connection, $query_unfiled_lib_id);
				//Test if there was any SQL error
					if(!$lib_set){
						die("Database Query Failed");
					} 
	$lib_row = mysqli_fetch_assoc($lib_set);
	$ref_id_library_stored = $lib_row["lib_id"];
					
	
	
	
	$title = mysql_prep($_POST["title"]);
	$author = mysql_prep($_POST["author"]);
	$year = mysql_prep($_POST["year"]);
	$abstract = mysql_prep($_POST["abstract"]);
	$pdf = mysql_prep($_POST["pdf"]);
	$address = mysql_prep($_POST["address"]);
	$annote = mysql_prep($_POST["annote"]);
	$book_title = mysql_prep($_POST["book_title"]);
	$chapter = mysql_prep($_POST["chapter"]);
	$cross_ref = mysql_prep($_POST["cross_ref"]);
	$edition = mysql_prep($_POST["edition"]);
	$eprint = mysql_prep($_POST["eprint"]);
	$how_published = mysql_prep($_POST["how_published"]);
	$institution = mysql_prep($_POST["institution"]);
	$journal = mysql_prep($_POST["journal"]);
	$bibtexkey = mysql_prep($_POST["bibtexkey"]);
	$publish_month = mysql_prep($_POST["publish_month"]);
	$note = mysql_prep($_POST["note"]);
	$issue_number = mysql_prep($_POST["issue_number"]);
	$organisation = mysql_prep($_POST["organisation"]);
	$pages = (int)($_POST["pages"]);
	$publisher = mysql_prep($_POST["publisher"]);
	$school = mysql_prep($_POST["school"]);
	$series = mysql_prep($_POST["series"]);
	$publish_type = mysql_prep($_POST["publish_type"]);
	$url = mysql_prep($_POST["url"]);
	$volume = mysql_prep($_POST["volume"]);
	$added_at = mysql_prep($_POST["added_at"]);
	
	
	//validations for form values(server side)
		$required_fields = array("title");
	    validate_presences($required_fields);
	
	$fields_with_max_lenghts = array("title" => 30);
	validate_max_length($fields_with_max_lenghts);
	
	if(!empty($errors)){
		$_SESSION["errors"] = $errors;
		redirect_to("home.php");
	}
	
	
	$query_ref_insert = "INSERT INTO reference (";
	$query_ref_insert .= " ref_author, ref_abstract, ref_pdf, ref_address, ref_annote, ref_book_title, ref_chapter, ref_cross_reference, ref_edition, ref_eprint,";
	$query_ref_insert .= " ref_how_published, ref_institution, ref_journal, ref_bibTexKey, ref_publish_month, ref_note, ref_issue_number, ref_organisation, ref_pages,";
	$query_ref_insert .= " ref_publisher, ref_school, ref_series, ref_title, ref_publish_type, ref_url, ref_volume, ref_year, ref_added_at, ref_id_library_stored";
	$query_ref_insert .= ") VALUES (";
	$query_ref_insert .= " '{$author}', '{$abstract}', '{$pdf}', '{$address}', '{$annote}', '{$book_title}', '{$chapter}', '{$cross_ref}', '{$edition}', '{$eprint}',";
	$query_ref_insert .= " '{$how_published}', '{$institution}', '{$journal}', '{$bibtexkey}', '{$publish_month}', '{$note}', '{$issue_number}', '{$organisation}', {$pages},";
	$query_ref_insert .= " '{$publisher}', '{$school}', '{$series}', '{$title}', '{$publish_type}', '{$url}', '{$volume}', '{$year}', '{$added_at}', '{$ref_id_library_stored}'";
	$query_ref_insert .= ")";
	
	$result_ref_insert = mysqli_query($connection, $query_ref_insert);
	confirm_query($result_ref_insert);
	
}else{
	//end: reference form submission
}

?>

<?php
//start: POST request for creating new library
if(isset($_POST["add_new_library_submit"])) {
	
	$email = $_SESSION["user_email"];
	$new_lib_name = mysql_prep($_POST["new_lib_name"]);
	add_new_library($new_lib_name, $email);
	
	
}else{
	
//this is probablt the get request	
	
}//end: POST request for creating new library
?>
<?php
//start: moving selected references to selected library
if(isset($_POST["move_to_selected_library_submit"]) && isset($_POST["move_libraries"]) && isset($_POST['ref_list'])){
	
	$ref_ids = $_POST['ref_list'];
	
	$target_lib_id = $_POST["move_libraries"];	
	move_references_to_selected_library($ref_ids, $target_lib_id);

		
	
}else{
	//this is probably a GET request
}//end: moving selected references to selected library

?>



<?php
//start: changing the active library
if(isset($_POST["change_active_library_submit"])){
	
		$selected_library_id = $_POST["libraries"];
	if($selected_library_id == "all_my_references"){
		//user has selected all my reference
		$reference_listing_request = true;
		$lib_listing_request = false;
	}else{
		$reference_listing_request = false;
		$lib_listing_request = true;
	}
	
}else{
	//this is probably a GET request
		$reference_listing_request = true;
		$lib_listing_request = false;

}

 //end: changing the active library
?>

<?php 
//start: editing the library name
if(isset($_POST["edit_library_submit"])){
	
	$edited_lib_name = mysql_prep($_POST["edited_lib_name"]);
	$selected_lib = $_POST["library_editing"];
	update_library_name($edited_lib_name, $selected_lib);
	
}else{
	//this is probably the GET request
}
//end: editing the library name
?>

<?php
//start: deletion of library

if(isset($_POST["delete_library_submit"]) && isset($_POST["library_listing_delete"])){
	$_SESSION["del_lib_id"] = $_POST["library_listing_delete"];
	$_SESSION["user_email"] = $_SESSION["user_email"];
	$library_has_reference = check_library_empty($_POST["library_listing_delete"]);
	$_SESSION["library_has_reference"] = $library_has_reference;
	redirect_to("delete.php");

}else{
	//this is probably the GET request 
}
 //end: deletion of library
?>

<?php 
//start: finding the trash id of the logged in user
$result_trash_id = find_trash_id_of_user($_SESSION["user_email"]);
	$row_trash_id = mysqli_fetch_assoc($result_trash_id);
	$trash_lib_id = $row_trash_id["lib_id"];
	
?>

<?php
//start: deleting from the trash
if(isset($_POST["delete_from_trash_submit"]) && null !== $_POST['ref_list']){
	$ref_ids = $_POST['ref_list'];	
	delete_references_from_trash($ref_ids);
	
}else{
	//this is probably the GET request
}
//end: deleting from the trash
?>

<?php
//start: sharing library
//1: check if user is registered, if not prompt user is not registered
//2: if user is registered then first always make delete query then
//3: make query entry for insertion
if(isset($_POST["share_library_submit"]) && null !== $_POST['library_listing_sharing'] && isset($_POST["email"])){
	//for now dont check for activation
	$user_registered = check_for_user_registration($_POST["email"]);//return true or false
	if($user_registered){
		//insert into the shared library
		$share_lib_id = $_POST['library_listing_sharing'];
		$shared_user_email = $_POST["email"];
		delete_entry_in_shared_library($shared_user_email, $share_lib_id);
		make_entry_in_shared_library($shared_user_email, $share_lib_id);
		
	}else{
		//prompt user that user does not exist
		$output = "<script>";
		$output .= "alert(\"The user is not registered\")";
		$output .= "</script>";
		echo $output;
		
	}
}else{
	// this is probably the GET request

}


//end: sharing library
?>

<?php 
//start: shared with 
//preparing variables for display of textarea
$shared_with_request = false;
$lib_shared_with_somebody = null;

if(isset($_POST["shared_with_submit"]) && $_POST["shared_by_user_library_listing"]){
	$share_lib_id = $_POST["shared_by_user_library_listing"];
	$result_lib_display_name = find_lib_display_name_by_id($share_lib_id);
	$row_lib_display_name = mysqli_fetch_assoc($result_lib_display_name);
	$share_lib_display_name = $row_lib_display_name["lib_display_name"];
	$user_email = $_SESSION["user_email"];
	
	$result_users_lib_shared_with = find_users_lib_shared_with($user_email, $share_lib_id);
	if(mysqli_num_rows($result_users_lib_shared_with) > 0){
		//library is shared with somebody
		$lib_shared_with_somebody = true;
		$shared_with_request = true;
		$share_with_users_list = array();
		while($row = mysqli_fetch_assoc($result_users_lib_shared_with)){
				$share_with_users_list[] = $row["shared_email_user_shared"];
		}		
	}else{
		//library is not shared with anybody
		$lib_shared_with_somebody = false;
		$shared_with_request = true;
	}
	
	
	
}else{
	//this is probably the GET request 
		
}
//end:  shared with
?>

<?php
//start: unshare library "step1: selecting library"
if(isset($_POST["select_library_submit"]) && isset($_POST["shared_library_listing"])){
	
		$selected_unshare_library_id = $_POST["shared_library_listing"];
		$_SESSION["unshare_lib_id"] = $selected_unshare_library_id;
		$unshare_lib_listing_request = true;
		$show_users_for_unsharing = true;

	
}else{
	//this is probably a GET request
$unshare_lib_listing_request = false;
$show_users_for_unsharing = false;

}

 //end: unshare library "step1: selecting library"
?>


<?php
//start: unshare library "step2: deleting user entry from shared library"
if(isset($_POST["unshare_submit"]) && null !== $_POST['users_list']){
	
	$shared_user_emails = $_POST['users_list'];
	$target_lib_id = $_SESSION["unshare_lib_id"];	
	foreach($shared_user_emails as $shared_user_email){
	delete_entry_in_shared_library($shared_user_email, $target_lib_id);
	}
	
}else{
	//this is probably a GET request
}//end: unshare library "step2: deleting user entry from shared library"

?>

<?php
$view_lib_shared_with_you = false; 
//start: displaying reference of library shared with you
if(isset($_POST["view_library_submit"]) && isset($_POST["library_shared_with_user_listing"])){
	$view_lib_shared_with_you = true;
	$lib_id_shared_with_you = $_POST["library_shared_with_user_listing"];
	
}else{
	//this is probably the GET request
	$view_lib_shared_with_you = false;
}

//end: displaying reference of library shared with you
?>


<?php
//start: move reference to trash
//print_r($_POST);
//exit;
if(isset($_POST["move_ref_to_trash_submit"]) && isset($_POST['ref_list'])){
	
	$ref_ids = $_POST['ref_list'];
	
	$target_lib_id = $trash_lib_id;	
	move_references_to_selected_library($ref_ids, $target_lib_id);	
	
	
}else{
	//this is probably the GET request
}	
// end: move reference to trash
?>

		<?php
// start: search results		
		$search_result = false;
		$search_request = false;
		if(isset($_POST["search_submit"])){
			$search_request = true;
		$search_author = mysql_prep(trim($_POST["search_author"]));
		$search_title = mysql_prep(trim($_POST["search_title"]));
		$search_year = mysql_prep(trim($_POST["search_year"]));
		
		
		
		
			global $result_search;
			$result_search = search_database_for_author($search_author, $search_title, $search_year);
			if(mysqli_num_rows($result_search) > 0){
				$search_result = true;

			}else{
				$search_result = false;
				
			}
		//print_r($final_search_ref_ids);
		}else{
			//this is probably the GET request
			$search_request = false;
			
		}
		// end: search results
		?>













<!DOCTYPE html>
<html>
<head>
<title>Bibliograhy Home</title>
<link rel="stylesheet" type="text/css" href="css/public.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="javascript/public.js"></script>
<script type="text/javascript" src="javascript/sorttable.js"></script>
</head>

<body>
<script type="text/javascript">
var checkflag = "false";
function check(field) {
	alert("heheheh");
  if (checkflag == "false") {
    for (i = 0; i < field.length; i++) {
      field[i].checked = true;
    }
    checkflag = "true";
    return "Uncheck All";
  } else {
    for (i = 0; i < field.length; i++) {
      field[i].checked = false;
    }
    checkflag = "false";
    return "Check All";
  }
}

</script>
<div id="wrapper">
		<div id="header">
		<h1>Bibliograhy Manager</h1><br>
		<?php echo message(); ?>
		<?php $errors = errors(); ?>
		<?php echo form_errors($errors); ?>
		<div id="user_info"><a href="edit_account.php">My Account</a> | 
		<?php $output = "Welcome: ";
				$output .= $_SESSION["user_name"];
				echo ($output);
		?>
		</div>
		<div id="logout">
		<a href="logout.php">LogOut</a>
		
		</div>
		</div>

		<div id="nav">
		
			<div id="change_active_library">
			<!-- Performing database query to get library listing -->
			<?php
				
				$result_change_active_lib_listing = find_all_libraries_for_user($_SESSION["user_email"]);
			
			?>
			<!-- Listing all user owned libraries-->
				<form action="home.php" method="post">
					<fieldset>
					<legend>Change active library</legend>
			 
			 <select name="libraries">
			 <option value="all_my_references" <?php if(!($lib_listing_request)){echo "selected"; } ?>>All My References</option>
			 
			 <?php
			 //getting user libraries dynamically
			 $selected_lib_trash = null;
				while($row = mysqli_fetch_assoc($result_change_active_lib_listing)){
					$dynamic_lib_listing =  "<option value=\"{$row["lib_id"]}\" ";
					if(($lib_listing_request) && ($selected_library_id == $row["lib_id"])){
						$selected = "selected";
						//if condition to determine whether the selected lib is trash
						if($trash_lib_id == $row["lib_id"]){
							$selected_lib_trash = true;
						}else{
							$selected_lib_trash = false;
						}
					}else{
						$selected = "";
					}
				$dynamic_lib_listing .= "{$selected}>";
				$dynamic_lib_listing .= "{$row["lib_display_name"]}</option>";
					echo ($dynamic_lib_listing);
					//echo "<option value=\"{$row["lib_id"]}\">{$row["lib_display_name"]}</option>";
				}
			 ?>
			 
			 
			 </select><br>
			 <input type="submit" name="change_active_library_submit" value="Change Active Library">
			 
			 </fieldset>
			 </form>
			 
			</div>
			<div id="create_new_library">

				<form action="home.php" method="post">
					<fieldset>
					<legend>Create New Library</legend>
					<input type="text" name="new_lib_name"><br>
			 <input type="submit" name="add_new_library_submit" value="Add New Library">
			 
			 </fieldset>
			 </form>
			 
			</div>
						<div id="edit_library">

				<form action="home.php" method="post">
					<fieldset>
					<legend>Edit Library Name</legend>
					<?php $result_libraries_editing = find_libraries_for_editing($_SESSION["user_email"])?>
					

					<select name="library_editing">
					<?php 
					while($row = mysqli_fetch_assoc($result_libraries_editing)){
					echo "<option value=\"{$row["lib_id"]}\">{$row["lib_display_name"]}</option>";
					}
					?>
					</select><br>
					
					<input type="text" name="edited_lib_name"><br>
			 <input type="submit" name="edit_library_submit" value="Edit Library Name">
			 
			 </fieldset>
			 </form>
			 
			</div>
			<div id="delete_library">

				<form action="home.php" method="post">
					<fieldset>
					<legend>Delete Library</legend>
					<?php $result_libraries_deletion = find_libraries_for_deletion($_SESSION["user_email"])?>
					<select name="library_listing_delete">
					<?php 
					while($row = mysqli_fetch_assoc($result_libraries_deletion)){
					echo "<option value=\"{$row["lib_id"]}\">{$row["lib_display_name"]}</option>";
					}
					?>
					</select><br>
					
					
			 <input type="submit" name="delete_library_submit" value="Delete Library" onClick="myFunction()">
			 
			 </fieldset>
			 </form>
			 
			</div>
				<div id="share_library">

				<form action="home.php" method="post">
					<fieldset>
					<legend>Share Library</legend>
					<?php $result_libraries_sharing = find_all_libraries_for_sharing($_SESSION["user_email"])?>
					
					Library: <select name="library_listing_sharing">
					
					<?php 
					while($row = mysqli_fetch_assoc($result_libraries_sharing)){
					echo "<option value=\"{$row["lib_id"]}\">{$row["lib_display_name"]}</option>";
					}
					?>
					</select><br>
					Share with:<br>
					Email:<input type="email" name="email" /><br>
					
					
			 <input type="submit" name="share_library_submit" value="Share Library">
			 
			 </fieldset>
			 </form>
			 
			</div>
				<div id="shared_with">

				<form action="home.php" method="post">
					<fieldset>
					<legend>Shared with</legend>
					<?php $result_libraries_sharing = find_all_libraries_for_sharing($_SESSION["user_email"])?>
					
					<!-- same list as share library but in different context-->
					Library: <select name="shared_by_user_library_listing">
					
					<?php 
					while($row = mysqli_fetch_assoc($result_libraries_sharing)){
					echo "<option value=\"{$row["lib_id"]}\">{$row["lib_display_name"]}</option>";
					}
					?>
					</select><br>
					<input type="submit" name="shared_with_submit" value="Shared with">
					<?php 
					//display text area when no shared with request is made
					if($shared_with_request == false){
					echo "<textarea rows=\"4\" cols=\"25\" name=\"users_list\" style=\"resize:none\" readonly>Users library shared with:</textarea>";
					}
					?>
					<?php 
					// display text area when library is shared with somebody
					if(($lib_shared_with_somebody == true) && ($shared_with_request == true)){
						

						$display_user_list = "Library ";
						$display_user_list .= "\"";
						$display_user_list .= $share_lib_display_name;
						$display_user_list .= "\"";
						$display_user_list .= " is shared with: ";
						$display_user_list .= "\n";
						foreach($share_with_users_list as $user) {
							$display_user_list .= $user;
							$display_user_list .= "\n";
						}
					echo "<textarea rows=\"4\" cols=\"25\" name=\"users_list\" style=\"resize:none\" readonly>$display_user_list</textarea>";
					}
					?>	
					<?php 
					// display text area when library is shared with nobody
					if(($lib_shared_with_somebody == false) && ($shared_with_request == true)){
						$display_user_list = "Library ";
						$display_user_list .= "\"";
						$display_user_list .= $share_lib_display_name;
						$display_user_list .= "\"";
						$display_user_list .= " is shared with nobody. ";						
						
						echo "<textarea rows=\"4\" cols=\"25\" name=\"users_list\" style=\"resize:none\" readonly>$display_user_list</textarea>";
					}
					?>
					
			
			 
			 </fieldset>
			 </form>
			 
			</div>	
				<div id="unshare_library">

				<form action="home.php" method="post">
					<fieldset>
					<legend>Unshare Library</legend>
					<!-- start of step1: selecting library to unshare-->
					<?php $result_libraries_shared_by_user = find_all_libraries_shared_by_user($_SESSION["user_email"])?>
					
					Library: <select name="shared_library_listing">
					
			 <?php

				while($row = mysqli_fetch_assoc($result_libraries_shared_by_user)){
					$dynamic_lib_for_unshare_listing =  "<option value=\"{$row["lib_id"]}\" ";
					if(($unshare_lib_listing_request) && ($selected_unshare_library_id == $row["lib_id"])){
						$selected = "selected";
					}else{
						$selected = "";
					}
				$dynamic_lib_for_unshare_listing .= "{$selected}>";
				$dynamic_lib_for_unshare_listing .= "{$row["lib_display_name"]}</option>";
					echo ($dynamic_lib_for_unshare_listing);
					
				}
			 ?>
					</select><br>
					<input type="submit" name="select_library_submit" value="Select Library">
					<!-- end of step1: selecting library to unshare-->
					<!-- start of step2: selecting users to unshare-->
					<?php
						if($unshare_lib_listing_request){ ?>
						<form action="home.php" method="post">
						<fieldset>
						<legend>Select Users:</legend>
						
						<?php 
						$user_email = $_SESSION["user_email"];
						$unshare_lib_id = $_SESSION["unshare_lib_id"];
						$result_users_lib_shared_with = find_users_lib_shared_with($user_email, $unshare_lib_id); 
						while($row = mysqli_fetch_assoc($result_users_lib_shared_with)){
							$display_users = "<input type=\"checkbox\" name=\"users_list[]\" value=\"{$row["shared_email_user_shared"]}\" />";
							$display_users .= " | ";
							$display_users .= $row["shared_email_user_shared"];
							$display_users .= "<br/>";
							//$display_users .= "mmmmmmm";
							echo $display_users;
						}
						
						
						?>
						<input type="submit" name="unshare_submit" value="Unshare">
						</fieldset>
						</form>
						
							
					<?php	} ?>
					<!-- end of step2: selecting users to unshare-->
					
			 
			 
			 </fieldset>
			 </form>
			 
			</div>
				<div id="shared_library_with_user">

				<form action="home.php" method="post">
					<fieldset>
					<legend>Shared with You</legend>
					<?php $result_libraries_shared_with_user = find_all_libraries_shared_with_user($_SESSION["user_email"]);?>
					
					Library: <select name="library_shared_with_user_listing">
					
					<?php 
					while($row = mysqli_fetch_assoc($result_libraries_shared_with_user)){
					$dynamic_lib_listing =  "<option value=\"{$row["lib_id"]}\" ";
					if(($view_lib_shared_with_you) && ($lib_id_shared_with_you == $row["lib_id"])){
						$selected = "selected";
					}else{
						$selected = "";
					}
				$dynamic_lib_listing .= "{$selected}>";
				$dynamic_lib_listing .= "{$row["lib_display_name"]}</option>";
					echo ($dynamic_lib_listing);						
					//echo "<option value=\"{$row["lib_id"]}\">{$row["lib_display_name"]}</option>";
					}
					
					?>
					</select><br>
					
			 <input type="submit" name="view_library_submit" value="View Library">
			 
			 </fieldset>
			 </form>
			 
			</div>			
				<div id="search_library">

				<form action="home.php" method="post" target="_blank\">
					<fieldset>
					<legend>Search References</legend>
					Author: <input type="text" name="search_author" value=" " /><br>
					Title: <input type="text" name="search_title" value=" " /><br>
					Year: <input type="text" name="search_year" value=" " /><br>

					
			 <input type="submit" name="search_submit" value="Search">
			 
			 </fieldset>
			 </form>
			 
			</div>			
		</div>

		<div id="section">
			<div id="ref_listing">
			<!-- populating reference list for All mt reference option for logged in user-->
			<form action="home.php" method="post" name="ref_list">
			<table style="width:100%" class="sortable">
				  <tr>
					<!--th><input type=button value="Check All" onClick="this.value=check(this.form.ref_list[])"></th-->
					<th class="sorttable_nosort" width="15px"><input type="button" id="Check_All" value="Check All" onClick="check_all(document.getElementsByClassName('chk_ref_list'));"></th>
					<th>Author</th>		
					<th>Title</th>
					<th>Year</th>		
					<th class="sorttable_nosort">Key</th>
					<th class="sorttable_nosort">PDF</th>		
					<th class="sorttable_nosort">URL</th>
				  </tr>

				  <?php
				  if($lib_listing_request && !$view_lib_shared_with_you && !$search_request){
					  //particular library listing
					  $result_all_reference_library = find_all_reference_by_lib_id($selected_library_id);
					 while($row = mysqli_fetch_assoc($result_all_reference_library)){
					  $reference_listing = "<tr>";
					  $reference_listing .= "<td><input type=\"checkbox\" name=\"ref_list[]\" class=\"chk_ref_list\" value=\"{$row["ref_id"]}\" /></td>";
					  $reference_listing .= "<td>{$row["ref_author"]}</td>";
											if($selected_lib_trash){
					  $reference_listing .=	"<td>{$row["ref_title"]}</td>";					
											}else{
					  $reference_listing .=	"<td><a href=\"view_reference.php?view_ref={$row["ref_id"]}\" target=\"_blank\">{$row["ref_title"]}</a></td>";	
											}						
					  $reference_listing .= "<td>{$row["ref_year"]}</td>";
					  $reference_listing .= "<td>{$row["ref_bibTexKey"]}</td>";
					  $reference_listing .= "<td>{$row["ref_pdf"]}</td>";
					  $reference_listing .= "<td>{$row["ref_url"]}</td>";
					  $reference_listing .= "</tr>";
					  echo ($reference_listing);
				  }
					  
					  
				  }elseif($reference_listing_request && !$view_lib_shared_with_you && !$search_request){
				  //all my reference listing
				  
				 $result_all_ref_email = find_all_reference_by_email_id($_SESSION["user_email"]);
				   while($row = mysqli_fetch_assoc($result_all_ref_email)){
					   if($row["ref_id_library_stored"] == $trash_lib_id){
						   continue;
					   }
				  $reference_listing = "<tr>";
				  $reference_listing .= "<td class='cbox'><input type=\"checkbox\" name=\"ref_list[]\" class=\"chk_ref_list\" value=\"{$row["ref_id"]}\" /></td>";
				  $reference_listing .= "<td>{$row["ref_author"]}</td>";
				  $reference_listing .= "<td><a href=\"view_reference.php?view_ref={$row["ref_id"]}\" target=\"_blank\">{$row["ref_title"]}</a></td>";
				  $reference_listing .= "<td class='year'>{$row["ref_year"]}</td>";
				  $reference_listing .= "<td>{$row["ref_bibTexKey"]}</td>";
				  $reference_listing .= "<td>{$row["ref_pdf"]}</td>";
				  $reference_listing .= "<td>{$row["ref_url"]}</td>";
				  $reference_listing .= "</tr>";
					echo ($reference_listing);  
				   }
				  
				  }elseif($view_lib_shared_with_you && !$search_request){
					  //listing references of library shared with you
					  $result_all_reference_library = find_all_reference_by_lib_id($lib_id_shared_with_you);
					 while($row = mysqli_fetch_assoc($result_all_reference_library)){
					  $reference_listing = "<tr>";
					  $reference_listing .= "<td><input type=\"checkbox\" name=\"ref_list[]\" class=\"chk_ref_list\" value=\"{$row["ref_id"]}\" /></td>";
					  $reference_listing .= "<td>{$row["ref_author"]}</td>";
					  $reference_listing .= "<td><a href=\"view_reference.php?view_ref={$row["ref_id"]}\" target=\"_blank\">{$row["ref_title"]}</a></td>";
					  $reference_listing .= "<td>{$row["ref_year"]}</td>";
					  $reference_listing .= "<td>{$row["ref_bibTexKey"]}</td>";
					  $reference_listing .= "<td>{$row["ref_pdf"]}</td>";
					  $reference_listing .= "<td>{$row["ref_url"]}</td>";
					  $reference_listing .= "</tr>";
					  echo ($reference_listing);
				  }					  
				  }elseif($search_request){
					  if(!$search_result){
						  $display_no_search = "<div id=\"no_search\">";
						  $display_no_search .= "<h4>No Items Found</h4>";
						  $display_no_search .= "</div>";
						  echo $display_no_search;
					  }
					  if($search_result){
						  
			 $search_count = mysqli_num_rows($result_search);
				
					echo "<h4>Items Found: $search_count</h4>";
					echo "<br>";
					
						 while($row = mysqli_fetch_assoc($result_search)){
						 if($row["ref_id"] == $trash_lib_id){
							 continue;
						 }
					  $reference_listing = "<tr>";
					  $reference_listing .= "<td><input type=\"checkbox\" name=\"ref_list[]\" class=\"chk_ref_list\" value=\"{$row["ref_id"]}\" /></td>";
					  $reference_listing .= "<td>{$row["ref_author"]}</td>";
					  
					  $reference_listing .=	"<td><a href=\"view_reference.php?view_ref={$row["ref_id"]}\" target=\"_blank\">{$row["ref_title"]}</a></td>";																	
					  $reference_listing .= "<td>{$row["ref_year"]}</td>";
					  $reference_listing .= "<td>{$row["ref_bibTexKey"]}</td>";
					  $reference_listing .= "<td>{$row["ref_pdf"]}</td>";
					  $reference_listing .= "<td>{$row["ref_url"]}</td>";
					  $reference_listing .= "</tr>";
					  echo ($reference_listing);
				  }
						  
					  }
				  }
				  ?>

			</table><br>
			

			<?php if(!$view_lib_shared_with_you){ ?>
			<fieldset>
			<?php
				$result_move_to_library = find_all_libraries_for_user($_SESSION["user_email"]);
			?>
			<legend>Move Selected to Library</legend>
			<select name="move_libraries">
			 
			 
			 <?php
			 //getting user libraries  dynamically for moving selected references to particular library 
				while($row = mysqli_fetch_assoc($result_move_to_library)){
					if($row["lib_id"] == $trash_lib_id){
						continue;
					}
					echo "<option value=\"{$row["lib_id"]}\">{$row["lib_display_name"]}</option>";
				}
			 ?>
			 
			 </select><br>
			 <input type="submit" name="move_to_selected_library_submit" value="Move Selected">
			 			<?php
				//display empty trash button 
				if($selected_lib_trash == true){
					$display_trash_button = "<div id=\"trash_button\">";
					$display_trash_button .= "<input type=\"submit\" name=\"delete_from_trash_submit\" value=\"Delete From Trash\" >";
					$display_trash_button .= "</div>";
					echo ($display_trash_button);
				}
				
					if($selected_lib_trash == false){
					$display_delete_button = "<div id=\"delete_button\">";
					$display_delete_button .= "<input type=\"submit\" name=\"move_ref_to_trash_submit\" value=\"Delete Reference\" >";
					$display_delete_button .= "</div>";
					echo ($display_delete_button);
				}
			?>
			
			</fieldset>
			<?php } ?>
			
			</form>

			</div>
			<div id="seperation">
			</div>
			
			<div id="reference_display">
			
			 <form action="home.php" method="post">
			 <table class="ref" style="width:100%">
			<tr><td class="label"> Title*:</td><td> <input type="text" name="title"></td><td class="label"> Author: </td><td><input type="text" name="author"></td><td class="label">Year:</td><td>	<input type="text" name="year"></td></tr>
			 <tr><td class="label">Abstract:</td><td> <textarea rows="4" cols="50" name="abstract"></textarea></td></tr>
			<tr> <td class="label">AttachPDF:</td><td> <input type="text" name="pdf"></td><td class="label">Address:</td><td> <input type="text" name="address"></td><td class="label">Annote:</td><td> <input type="text" name="annote"></td></tr>
			
			<tr><td class="label"> Book Title:</td><td> <input type="text" name="book_title"></td><td class="label"> Chapter:</td><td> <input type="text" name="chapter"></td><td class="label"> Cross Reference: </td><td> <input type="text" name="cross_ref"></td></tr>
			<tr><td class="label"> Edition:</td><td> <input type="text" name="edition"></td><td class="label"> E-print:</td><td> <input type="text" name="eprint"></td><td class="label"> How Published:</td><td> <input type="text" name="how_published"></td></tr>			
						
			<tr><td class="label"> Institution:</td><td> <input type="text" name="institution"></td><td class="label"> Journal:</td><td> <input type="text" name="journal"></td><td class="label"> BibTexKey: </td><td><input type="text" name="bibtexkey"></td></tr>		
			
			<tr><td class="label"> Note:</td><td> <textarea rows="4" cols="50" name="note"></textarea></td></tr>
			<tr><td class="label"> Publish Month:</td><td> <input type="text" name="publish_month"></td><td class="label"> Issue Number:</td><td> <input type="text" name="issue_number"></td><td class="label"> Organisation:</td><td> <input type="text" name="organisation"></td></tr>
			
			<tr><td class="label"> Pages:</td><td> <input type="text" name="pages"></td><td class="label"> Publisher:</td><td> <input type="text" name="publisher"></td><td class="label"> School: </td><td><input type="text" name="school"></td></tr>
			
			
			<tr><td class="label"> Series:</td><td> <input type="text" name="series"></td><td class="label"> Publish Type:</td><td> <input type="text" name="publish_type"></td><td class="label"> Volume:</td><td> <input type="text" name="volume"></td></tr>

			
			<tr><td class="label"> Added At: </td><td><input type="text" name="added_at"></td><td class="label">URL: </td><td><input type="text" name="url"></td></tr>
			 
			 </table><br>
			 <input type="submit" name="ref_submit" value="ADD REFERENCE"><br>
			 &nbsp;
			 </form>
			</div>

		

		</div>

		<div id="footer">
		Copyright © BibManager
		</div>
</div>

</body>

</html>
			<?php 
				//Release returned data
				//mysqli_free_result($result);
			?>

<?php include("includes/db_close.php") ?>