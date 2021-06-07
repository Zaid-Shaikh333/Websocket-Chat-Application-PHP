<?php

if(isset($_POST['login']))
{
	$user_name = $_POST['user_name'];
	$user_password = $_POST['user_password'];
	$user_email = $_POST['user_email'];

	include("database/connection.php");

	$query = "SELECT * FROM chat_user WHERE user_email = '$user_email'";
	$execute = mysqli_query($conn,$query);
	$fetch = mysqli_fetch_assoc($execute);

	$hash = $fetch['user_password'];
	$user_profile = $fetch['user_profile'];
	$user_online_status = $fetch['user_login_status'];
	if(mysqli_num_rows($execute) > 0)
	{
		if(password_verify($user_password, $hash))
		{
			session_start();
			// Set user Login Status as Online.
			$user_online_status = 'Online';
			// Create a Unique Token for each Login
			$token = md5(uniqid());

			$set_user_online = "UPDATE chat_user SET user_login_status = '$user_online_status',user_token = '$token'
			WHERE user_email = '$user_email'";
			$exe = mysqli_query($conn,$set_user_online);


			$arr = array($user_name,$user_password,$user_email,$user_profile,$user_online_status,$token); 
			$_SESSION['user'] = $arr; // Creating array of Session Values
			echo "<script>window.alert('Welcome '".$user_name."'. Ready to Chat?')</script>";
			echo "<script>window.open('Chatroom.php','_self')</script>";
		}
		else
		{
			echo "<script>window.alert('Wrong Password. Please try again.')</script>";
			echo "<script>window.open('login.php','_self')</script>";
		}
	}
	else
	{
		echo "<script>window.alert('User not Registered. Please register first.')</script>";
		echo "<script>window.open('register.php','_self')</script>";
	}
}

?>