<?php  require_once("session.php"); ?>
<?php  require_once("includes/db_connect.php"); ?>
<?php  require_once("functions.php"); ?>
<?php  require_once("validation_functions.php"); ?>
<?php confirm_logged_in(); ?>







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
		<div id="view_reference">

			<?php $view_ref_id = $_GET["view_ref"];
			$selected_ref_result = find_reference_by_ref_id($view_ref_id);
			$row = mysqli_fetch_assoc($selected_ref_result);
			
			?>
			 
			 <table class="ref" style="width:100%">
			<tr><td class="label" col width="130"> Title*:</td><td col width="130"><?php echo ($row["ref_title"]); ?></td><td  class="label" col width="130"> Author: </td><td col width="130"><?php echo ($row["ref_author"]); ?></td><td  class="label" col width="130">Year:</td><td col width="130"><?php echo ($row["ref_year"]); ?></td></tr>
			
			 
			 <tr><td  class="label">Abstract:</td><td><?php echo ($row["ref_abstract"]); ?></td></tr>
			<tr> <td  class="label">AttachPDF:</td><td><?php echo ($row["ref_pdf"]); ?></td><td  class="label">Address:</td><td><?php echo ($row["ref_address"]); ?></td><td  class="label">Annote:</td><td><?php echo ($row["ref_annote"]); ?></td></tr>

			<tr><td  class="label"> Book Title:</td><td><?php echo ($row["ref_book_title"]); ?></td><td  class="label"> Chapter:</td><td><?php echo ($row["ref_chapter"]); ?></td><td  class="label"> Cross Reference: </td><td><?php echo ($row["ref_cross_reference"]); ?></td></tr>
			
			<tr><td  class="label"> Edition:</td><td><?php echo ($row["ref_edition"]); ?></td><td class="label"> E-print:</td><td><?php echo ($row["ref_eprint"]); ?></td><td class="label"> How Published:</td><td><?php echo ($row["ref_how_published"]); ?></td></tr>

			<tr><td class="label"> Institution:</td><td><?php echo ($row["ref_institution"]); ?></td><td class="label"> Journal:</td><td><?php echo ($row["ref_journal"]); ?></td><td class="label"> BibTexKey: </td><td><?php echo ($row["ref_bibTexKey"]); ?></td></tr>

			
			<tr><td class="label"> Note:</td><td><?php echo ($row["ref_note"]); ?></td></tr>
			
			<tr><td class="label"> Publish Month:</td><td><?php echo ($row["ref_publish_month"]); ?></td><td class="label"> Issue Number:</td><td><?php echo ($row["ref_issue_number"]); ?></td><td class="label"> Organisation:</td><td><?php echo ($row["ref_organisation"]); ?></td></tr>

			<tr><td class="label"> Pages:</td><td><?php echo ($row["ref_pages"]); ?></td><td class="label"> Publisher:</td><td><?php echo ($row["ref_publisher"]); ?></td><td class="label"> School: </td><td><?php echo ($row["ref_school"]); ?></td></tr>

			<tr><td class="label"> Series:</td><td><?php echo ($row["ref_series"]); ?></td><td class="label"> Publish Type:</td><td> <?php echo ($row["ref_publish_type"]); ?></td><td class="label"> URL:</td><td><?php echo ($row["ref_url"]); ?></td></tr>

			<tr><td class="label"> Volume:</td><td><?php echo ($row["ref_volume"]); ?></td><td class="label"> Added At: </td><td><?php echo ($row["ref_added_at"]); ?></td></tr>
		
			 
			 </table><br>
			 <div id="done_view">
			 <?php echo "<a href=\"home.php\" >DONE VIEWING</a>"; ?>
			 </div><br>&nbsp;
			 <?php echo "<a href=\"edit_reference.php?edit_ref={$view_ref_id}\" target=\"_blank\">EDIT REFERENCE</a>"; ?>
			 <br>
			 &nbsp;
			 
		</div>
		<div id="footer">
		Copyright © BibManager
		</div>
</div>


</body>
</html>