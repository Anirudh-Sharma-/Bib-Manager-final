<?php
function redirect_to($new_location){
	header("Location: " . $new_location);
	exit;
} 
?>

<?php
function mysql_prep($string){
	global $connection;
	
	$escaped_string = mysqli_real_escape_string($connection, $string);
	return $escaped_string;
} 
?>





<?php 
function password_encrypt($password){
	$hash_format = "$2y$10$"; //tells php to use blowfish with a cost of 10
	$salt_length = 22;			//blowfish salts should be 22 characters or more
	$salt = generate_salt($salt_length);
	$format_and_salt = $hash_format . $salt;
	$hash = crypt($password, $format_and_salt);
	return $hash;
	
}
?>

<?php
function generate_salt($length){
	//not 100% unique, not 100% random, but good enough for a salt
	//MD5 returns 32 characters
	$unique_random_string = md5(uniqid(mt_rand(), true));
	
	//valid characters for a salt are [a-zA-Z0-9./]
	$base64_string = base64_encode($unique_random_string);
	
	//but not '+' which is valid in base64 encoding
	$modified_base64_string = str_replace('+', '.', $base64_string);
	
	//truncate string to correct length
	$salt = substr($modified_base64_string, 0, $length);
	
	return $salt;
} 
?>

<?php
function password_check($password, $existing_hash){
	//existing hash contains format and salt at start
	$hash = crypt($password, $existing_hash);

	$hash = substr($hash, 0, -10);
	if($hash === $existing_hash){
		return true;
	}else{
		return false;
	}
} 
?>


<?php
function find_user_by_username($username){
	global $connection;
	
	$safe_username = mysqli_real_escape_string($connection, $username);
	
	$query = "SELECT * ";
	$query .= "FROM user_register ";
	$query .= "WHERE user_name = '{$safe_username}' ";
	$query .= "LIMIT 1";
	//echo ($query);
	$user_set = mysqli_query($connection, $query);
	
	if($user = mysqli_fetch_assoc($user_set)){
		return $user;
	}else{
		return null;
	}
} 
?>

<?php
function status_check($user_id){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM user_register ";
	$query .= "WHERE user_id = '{$user_id}'";
	$query .= "AND status = 'TRUE' ";
	$query .= "LIMIT 1";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	if(mysqli_fetch_assoc($result) > 0){
		return true;
	}else{
		return false;
	}
} 
?>

<?php
function attempt_login($username, $password){
	
	$user = find_user_by_username($username);
	if($user){

		//found admin, now check password
		if(password_check($password, $user["user_password"])){
			//password matches
			if(status_check($user["user_id"])){
			return $user;
			}else{
				return false;
			}
		}else{
			//password does not matches
			return false;
		}
		
	}else{
		//user not found
		return false;
	}
}
 
?>


<?php
function confirm_query($result_set){
	if(!$result_set){
		die("Database query failed");
	}
}
?>

<?php
function confirm_logged_in(){
	if(!isset($_SESSION['user_id'])){
		redirect_to("login.php");
	}
}
?>

<?php
function find_all_reference_by_email_id($email){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM reference r ";
	$query .= "LEFT JOIN library l ";
	$query .= "ON r.ref_id_library_stored = l.lib_id ";
	$query .= "WHERE lib_owner_email='{$email}'";
	
	$result_all_ref_email = mysqli_query($connection, $query);
	confirm_query($result_all_ref_email);
	
	return $result_all_ref_email;
	
} 
?>

<?php 
function add_new_library($new_lib_name, $email){
	global $connection;
	
		$query = "INSERT INTO library (";
		$query .= " lib_display_name, lib_owner_email";
		$query .= ") VALUES (";
		$query .= " '{$new_lib_name}', '{$email}'";
		$query .= ")";
		
		$result = mysqli_query($connection, $query);
		confirm_query($result);
}

?>

<?php
function find_all_libraries_for_user($email){
	global $connection;
	
					//Perform database query
				$query = "SELECT * ";
				$query .= "FROM library ";
				$query .= "WHERE lib_owner_email='{$_SESSION["user_email"]}'";
				$result = mysqli_query($connection, $query);
				//Test if there was any SQL error
					if(!$result){
						die("Database Query Failed");
					}
					
				return $result;
	
	
	
}
 
?>

<?php
function find_all_libraries_for_sharing($email){
	global $connection;
	
					//Perform database query
				$query = "SELECT * ";
				$query .= "FROM library ";
				$query .= "WHERE lib_owner_email='{$_SESSION["user_email"]}' ";
				$query .= "AND lib_display_name != 'Trash'";
				$result = mysqli_query($connection, $query);
				//Test if there was any SQL error
					if(!$result){
						die("Database Query Failed");
					}
					
				return $result;
	
	
	
}
 
?>

<?php
function move_references_to_selected_library($ref_ids, $target_lib_id){
	global $connection;
	
	foreach ($ref_ids as $ref_id) {
    $query = "UPDATE reference SET ref_id_library_stored = '{$target_lib_id}' WHERE ref_id = '{$ref_id}'";
    $result = mysqli_query($connection, $query);
	confirm_query($result);
}
} 
?>

<?php
function change_user_email_in_shared_library($user_email, $new_email){
	global $connection;
	
	$query1 = "SELECT * FROM shared_library WHERE shared_email_user_shared = '{$user_email}'";
    $result1 = mysqli_query($connection, $query1);
	confirm_query($result1);
	global $count = array();
	$count = 0;
		while($row = mysqli_fetch_assoc($result1)){
			$count[] = $row["shared_lib_id"];
		}
		
	foreach ($count as $c) {
    $query2 = "UPDATE shared_library SET shared_email_user_shared = '{$new_email}' WHERE shared_lib_id = '{$c}'";
    $result2 = mysqli_query($connection, $query2);
	confirm_query($result2);
}
	
	
}
 
?>



<?php 
function find_all_reference_by_lib_id($lib_id){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM reference ";
	$query .= "WHERE ref_id_library_stored = '{$lib_id}'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	return $result;
}

?>

<?php
function find_libraries_for_editing($user_email){
	global $connection;
	$query = "SELECT * ";
	$query .= "FROM library ";
	$query .= "WHERE lib_owner_email = '{$user_email}' ";
	$query .= "AND lib_display_name != 'Trash' ";
	$query .= "AND lib_display_name != 'Unfiled'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	return $result;
}
 
?>
<?php
function update_library_name($edited_lib_name, $selected_lib){
	global $connection;
	
	$query = "UPDATE library SET lib_display_name = '{$edited_lib_name}' WHERE lib_id = '{$selected_lib}'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
} 
?>

<?php
function find_libraries_for_deletion($user_email){
	global $connection;
	$query = "SELECT * ";
	$query .= "FROM library ";
	$query .= "WHERE lib_owner_email = '{$user_email}' ";
	$query .= "AND lib_display_name != 'Trash' ";
	$query .= "AND lib_display_name != 'Unfiled'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	return $result;
}
 
?>

<?php
function check_library_empty($lib_id){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM reference ";
	$query .= "WHERE ref_id_library_stored = '{$lib_id}'";
	$query .= "LIMIT 1";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	if(mysqli_num_rows($result) > 0){
		return true;
	}else{
		return false;
	}
	
} 
?>

<?php 
function find_libraries_for_moving_references($lib_id, $user_email){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM library ";
	$query .= "WHERE lib_owner_email = '{$user_email}' ";
	$query .= "AND lib_id != '{$lib_id}'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	return $result;
}
?>


<?php
function delete_library_by_id($lib_id_to_be_deleted){
	global $connection;
	
	$query = "DELETE FROM ";
	$query .= "library ";
	$query .= "WHERE lib_id = '{$lib_id_to_be_deleted}' ";
	$query .= "LIMIT 1";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
		
}
 
?>



<?php 
function find_trash_id_of_user($user_email){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM library ";
	$query .= "WHERE lib_owner_email = '{$user_email}' ";
	$query .= "AND lib_display_name = 'Trash'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	return $result;
}

?>

<?php
function delete_references_from_trash($ref_ids){
	global $connection;
	
	foreach ($ref_ids as $ref_id) {
    $query = "DELETE FROM reference WHERE ref_id = '{$ref_id}'";
    $result = mysqli_query($connection, $query);
	confirm_query($result);
}
} 
?>

<?php
function check_for_user_registration($user_email){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM user_register ";
	$query .= "WHERE user_email = '{$user_email}' ";
	$query .= "LIMIT 1";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
		if(mysqli_num_rows($result) > 0){
		return true;
	}else{
		return false;
	}
	
	
}
 
?>

<?php 
function make_entry_in_shared_library($shared_user_email, $share_lib_id){
	global $connection;
	
		$query = "INSERT INTO shared_library (";
		$query .= " shared_id_lib_shared, shared_email_user_shared";
		$query .= ") VALUES (";
		$query .= " '{$share_lib_id}', '{$shared_user_email}'";
		$query .= ")";
		
		$result = mysqli_query($connection, $query);
		confirm_query($result); 
}

?>

<?php
//find the users with whom the particular library is shared with
function find_users_lib_shared_with($user_email, $share_lib_id){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM shared_library s ";
	$query .= "LEFT JOIN library l ";
	$query .= "ON l.lib_id = s.shared_id_lib_shared ";
	$query .= "WHERE lib_owner_email='{$user_email}' ";
	$query .= "AND lib_id='{$share_lib_id}'";
	
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	return $result;
}
 
?>

<?php 
function delete_entry_in_shared_library($shared_user_email, $share_lib_id){
	global $connection;
	
	$query = "DELETE FROM ";
	$query .= "shared_library ";
	$query .= "WHERE shared_id_lib_shared = '{$share_lib_id}' ";
	$query .= "AND shared_email_user_shared = '{$shared_user_email}' ";
	$query .= "LIMIT 1";
	$result = mysqli_query($connection, $query);
	confirm_query($result);	
}
?>

<?php
function find_lib_display_name_by_id($lib_id){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM library ";
	$query .= "WHERE lib_id = '{$lib_id}'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
		return $result;
} 
?>

<?php
function find_all_libraries_shared_by_user($user_email){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM library l ";
	$query .= "RIGHT JOIN shared_library s ";
	$query .= "ON l.lib_id = s.shared_id_lib_shared ";
	$query .= "WHERE lib_owner_email='{$user_email}' ";
	$query .= "GROUP BY lib_id";

	
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	return $result;
} 
?>

<?php
function find_all_libraries_shared_with_user($user_email){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM library l ";
	$query .= "RIGHT JOIN shared_library s ";
	$query .= "ON l.lib_id = s.shared_id_lib_shared ";
	$query .= "WHERE shared_email_user_shared='{$user_email}' ";
	

	
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	return $result;
} 
?>

<?php
function find_reference_by_ref_id($ref_id){
global $connection;

$query = "SELECT * ";
$query .= "FROM reference ";
$query .= "WHERE ref_id = '{$ref_id}'";

	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	return $result;

	
}
?>

<?php
function search_database_for_author($search_author, $search_title, $search_year){
	global $connection;
	
	$query = "SELECT DISTINCT * ";
	$query .= "FROM reference ";
	$query .= "WHERE 1=1";
	if($search_author != " "){
		$query .= " AND ref_author LIKE '%{$search_author}%'";
	}
	if($search_title != " "){
		$query .= " AND ref_author LIKE '%{$search_title}%'";
	}
	if($search_year != " "){
		$query .= " AND ref_author LIKE '%{$search_year}%'";
	}	
	//echo $query;
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	return $result;
	
} 
?>

<?php 
function change_user_name($user_id, $new_username){
	global $connection;

	$query = "UPDATE user_register SET user_name = '{$new_username}' WHERE user_id = '{$user_id}'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	
	
}

?>


<?php 
function change_user_password($user_id, $new_hashed_password){
		global $connection;

	$query = "UPDATE user_register SET user_password = '{$new_hashed_password}' WHERE user_id = '{$user_id}'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
}

?>


<?php
function change_user_email($user_id, $new_email){
	global $connection;

	$query = "UPDATE user_register SET user_email = '{$new_email}' WHERE user_id = '{$user_id}'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	
} 
?>

<?php
function change_acount_status_to_inactive($user_id){
	global $connection;

	$query = "UPDATE user_register SET status = 'FALSE' WHERE user_id = '{$user_id}'";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	
	
} 
?>


<?php


 
?>











