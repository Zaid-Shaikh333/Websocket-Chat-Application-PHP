<?php


function get_users_list($user_email)
{
	include("database/connection.php");

	$query = "SELECT user_name,user_email,user_login_status FROM chat_user WHERE user_email != '$user_email'";
	$execute = mysqli_query($conn,$query);
	
	return $execute;
}

function set_user_offline($user_email)
{
	include('database/connection.php');
	$user_login_status = 'Offline';
	$set_user_offline = "UPDATE chat_user SET user_login_status = '$user_login_status' WHERE user_email = '$user_email'";
	$execute = mysqli_query($conn,$set_user_offline);

	if(isset($execute))
		return "Offline";
}

function update_connection_id($resourceID,$user_token)
{
	include('database/connection.php');
	$query = "UPDATE chat_user SET user_connection_id = '$resourceID' WHERE user_token = '$user_token'";
	$execute = mysqli_query($conn,$query);
	
}

//function get_single_chat_messages($sender,$receiver)


?>