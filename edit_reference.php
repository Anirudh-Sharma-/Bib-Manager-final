<?php  require_once("session.php"); ?>
<?php  require_once("includes/db_connect.php"); ?>
<?php  require_once("functions.php"); ?>
<?php  require_once("validation_functions.php"); ?>
<?php confirm_logged_in(); ?>

<?php
			$edit_ref_id = $_GET["edit_ref"]; 
			$selected_ref_result = find_reference_by_ref_id($edit_ref_id);
			$row = mysqli_fetch_assoc($selected_ref_result);
?>


<?php
//start: update reference
if(isset($_POST["update_ref_submit"])){
	
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
		redirect_to("edit_reference.php?edit_ref=$edit_ref_id");
	}
	

	
	$query_ref_update = "UPDATE reference SET ref_author='{$author}',ref_abstract='{$abstract}',ref_pdf='{$pdf}',ref_address='{$address}',ref_annote='{$annote}',ref_book_title='{$book_title}',ref_chapter='{$chapter}',ref_cross_reference='{$cross_ref}',ref_edition='{$edition}',ref_eprint='{$eprint}',ref_how_published='{$how_published}',ref_institution='{$institution}',ref_journal='{$journal}',ref_bibTexKey='{$bibtexkey}',ref_publish_month='{$publish_month}',ref_note='{$note}',ref_issue_number='{$issue_number}',ref_organisation='{$organisation}',ref_pages={$pages},ref_publisher='{$publisher}',ref_school='{$school}',ref_series='{$series}',ref_title='{$title}',ref_publish_type='{$publish_type}',ref_url='{$url}',ref_volume='{$volume}',ref_year='{$year}',ref_added_at='{$added_at}' WHERE ref_id='{$edit_ref_id}'";
	
	$result_ref_update = mysqli_query($connection, $query_ref_update);
	confirm_query($result_ref_update);
	
	
	redirect_to("view_reference.php?view_ref=$edit_ref_id");
	
}else{
	// this is probably the GET request
}



 //end: update reference
?>



<!DOCTYPE html>
<html>
<head>
<title>Bibliograhy Home</title>
<link rel="stylesheet" type="text/css" href="css/public.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="javascript/public.js"></script>

</head>

<body>
<div id="wrapper">
		<div id="header">
		<h1>Bibliograhy Manager</h1><br>
		<?php echo message(); ?>
		<?php $errors = errors(); ?>
		<?php echo form_errors($errors); ?>
		<div id="user_info"><a href="#">My Account</a> | 
		<?php $output = "Welcome: ";
				$output .= $_SESSION["user_name"];
				echo ($output);
		?>
		</div>
		<div id="logout">
		<a href="logout.php">LogOut</a>
		
		</div>
		</div>
		<div id="edit_reference">
		<form action="edit_reference.php?edit_ref=<?php echo ($edit_ref_id); ?>" method="post">
			 <table class="ref" style="width:100%">
			<tr><td  class="label"> Title*:</td><td> <input type="text" name="title" value="<?php echo ($row["ref_title"]); ?>"></td><td  class="label"> Author: </td><td><input type="text" name="author" value="<?php echo ($row["ref_author"]); ?>"></td><td class="label">Year:</td><td>	<input type="text" name="year" value="<?php echo ($row["ref_year"]); ?>"></td></tr>
			
			 <tr><td class="label">Abstract:</td><td> <textarea rows="4" cols="50" name="abstract"><?php echo ($row["ref_abstract"]); ?></textarea></td></tr>
			 
			<tr> <td class="label">AttachPDF:</td><td> <input type="text" name="pdf" value="<?php echo ($row["ref_pdf"]); ?>"></td><td class="label">Address:</td><td> <input type="text" name="address" value="<?php echo ($row["ref_address"]); ?>"></td><td class="label">Annote:</td><td> <input type="text" name="annote" value="<?php echo ($row["ref_annote"]); ?>"></td></tr>
			
			<tr><td class="label"> Book Title:</td><td> <input type="text" name="book_title" value="<?php echo ($row["ref_book_title"]); ?>"></td><td class="label"> Chapter:</td><td> <input type="text" name="chapter" value="<?php echo ($row["ref_chapter"]); ?>"></td><td class="label"> Cross Reference: </td><td> <input type="text" name="cross_ref" value="<?php echo ($row["ref_cross_reference"]); ?>"></td></tr>
		
			<tr><td class="label"> Edition:</td><td> <input type="text" name="edition" value="<?php echo ($row["ref_edition"]); ?>"></td><td class="label"> E-print:</td><td> <input type="text" name="eprint" value="<?php echo ($row["ref_eprint"]); ?>"></td><td class="label"> How Published:</td><td> <input type="text" name="how_published" value="<?php echo ($row["ref_how_published"]); ?>"></td></tr>
		
			<tr><td class="label"> Institution:</td><td> <input type="text" name="institution" value="<?php echo ($row["ref_institution"]); ?>"></td><td class="label"> Journal:</td><td> <input type="text" name="journal" value="<?php echo ($row["ref_journal"]); ?>"></td><td class="label"> BibTexKey: </td><td><input type="text" name="bibtexkey" value="<?php echo ($row["ref_bibTexKey"]); ?>"></td></tr>
			
			
			<tr><td class="label"> Note:</td><td> <textarea rows="4" cols="50" name="note"><?php echo ($row["ref_note"]); ?></textarea></td></tr>
			
			<tr><td class="label"> Publish Month:</td><td> <input type="text" name="publish_month" value="<?php echo ($row["ref_publish_month"]); ?>"></td><td class="label"> Issue Number:</td><td> <input type="text" name="issue_number" value="<?php echo ($row["ref_issue_number"]); ?>"></td><td class="label"> Organisation:</td><td> <input type="text" name="organisation" value="<?php echo ($row["ref_organisation"]); ?>"></td></tr>
			<tr></tr>
			<tr></tr>
			<tr><td class="label"> Pages:</td><td> <input type="text" name="pages" value="<?php echo ($row["ref_pages"]); ?>"></td><td class="label"> Publisher:</td><td> <input type="text" name="publisher" value="<?php echo ($row["ref_publisher"]); ?>"></td><td class="label"> School: </td><td><input type="text" name="school" value="<?php echo ($row["ref_school"]); ?>"></td></tr>
		
			<tr><td class="label"> Series:</td><td> <input type="text" name="series" value="<?php echo ($row["ref_series"]); ?>"></td><td class="label"> Publish Type:</td><td> <input type="text" name="publish_type" value="<?php echo ($row["ref_publish_type"]); ?>"></td><td class="label"> URL:</td><td> <input type="text" name="url" value="<?php echo ($row["ref_url"]); ?>"></td></tr>
			
			<tr><td class="label"> Volume:</td><td> <input type="text" name="volume" value="<?php echo ($row["ref_volume"]); ?>"></td><td class="label"> Added At: </td><td><input type="text" name="added_at" value="<?php echo ($row["ref_added_at"]); ?>"></td></tr>
			
			 
			 </table> 
			 &nbsp;
			 <input type="submit" name="update_ref_submit" value="UPDATE REFERENCE"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo "<a href=\"view_reference.php?view_ref={$edit_ref_id}\" >CANCEL</a>"; ?><br>&nbsp;
			
			 
			 </form>
		</div>
				<div id="footer">
		Copyright © BibManager
		</div>
		
</div>
</body>		
</html>		