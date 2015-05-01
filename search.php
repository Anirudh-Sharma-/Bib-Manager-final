<?php  require_once("session.php"); ?>
<?php  require_once("includes/db_connect.php"); ?>
<?php  require_once("functions.php"); ?>
<?php  require_once("validation_functions.php"); ?>
<?php confirm_logged_in(); ?>

<?php 
//start: finding the trash id of the logged in user
$result_trash_id = find_trash_id_of_user($_SESSION["user_email"]);
	$row_trash_id = mysqli_fetch_assoc($result_trash_id);
	$trash_lib_id = $row_trash_id["lib_id"];
	
?>
		<?php 
		$search_result = false;
		if(isset($_POST["search_submit"])){
		$search_author = mysql_prep(trim($_POST["search_author"]));
		$search_title = mysql_prep(trim($_POST["search_title"]));
		$search_year = mysql_prep(trim($_POST["search_year"]));
		
		$final_search_ref_ids = array();
		
		
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
		}
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
		
		
		<div id="search_results">
		<?php if(!$search_result){ ?>
			echo <div id="no_search">
			 
				
					 <h4>No Items Found</h4>
					 </div>
			<?php	} ?>
			 
			
			<?php if($search_result){ ?>
			<div id="search_found">
			 <?php 
			 $search_count = mysqli_num_rows($result_search);
				
					echo "<h4>Items Found: $search_count</h4>";
					echo "<br>";
				
			 ?>
			 <form>
			 <table style="width:100%">
				  <tr>
					
					<th><input type="button" id="Check_All" value="Check All" onClick="check_all(document.getElementsByClassName('chk_ref_list'));"></th>
					<th>Author</th>		
					<th>Title</th>
					<th>Year</th>		
					<th>Key</th>
					<th>PDF</th>		
					<th>URL</th>
				  </tr>

				  <?php
				  

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
					  
					  
				  
				  ?>

			</table><br>
			 
			 
			 
			 
			</div>
			<?php } ?>

		</div>
		
		
		<div id="footer">
		Copyright © W3Schools.com
		</div>
		
		
</div>
</body>
</html>