<?php

$errors = array();

function fieldname_as_text($fieldname){
	$fieldname = str_replace("_", " ", $fieldname);
	$fieldname = ucfirst($fieldname);
	return $fieldname;
} 

// *presence
//use trim so empty spaces dont count
//use === to avoid false positives
//empty would consider 0 to be empty
function has_presence($value){
	return isset($value) && $value !== "";
}

function validate_presences($required_fields){
	global $errors;
	foreach($required_fields as $field){
		$value = trim($_POST[$field]);
		if(!has_presence($value)){
			$errors[$field] = ucfirst($field) . " cannot be blank";
		}
	}
}

//*string length
//max length
function has_max_length($value, $max){
	return strlen($value) <= $max;
}

//*inclusion in a set
function has_inclusion_in($value, $set){
		return in_array($value, $set);
}

function validate_max_length($fields_with_max_lengths){
	global $errors;
	//Expects an assoc array
	foreach($fields_with_max_lengths as $field => $max){
		$value = trim($_POST["$field"]);
		if(!has_max_length($value, $max)){
				$errors[$field] = fieldname_as_text($field) . " is too long";
		}
	}
}

function validate_username_exist($username){
	global $connection;
	global $errors;
	$query = "SELECT * FROM user_register WHERE user_name = '{$username}'";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		if(!(mysqli_num_rows($result) > 0)){
			$errors[$field] = "username already exists."
		}
}

function validate_email_exist($email){
	global $connection;
	global $errors;
	$query = "SELECT * FROM user_register WHERE user_email = '{$email}'";
		$result = mysqli_query($connection, $query);
		confirm_query($result);
		if(!(mysqli_num_rows($result) > 0)){
			$errors[$field] = "email id already exists."
		}
}


function form_errors($errors=array()){
	$output = "";
	if(!empty($errors)){
		$output .= "<div class = \"errors\">";
		$output .= "Please fix the following errors:";
		$output .= "<ul>";
		foreach($errors as $key => $error){
				$output .= "<li>{$error}</ul>";
		}
		$output .= "</ul>";
		$output .= "</div>";
		
	}
	return $output;
}











?>