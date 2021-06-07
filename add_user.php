<?php

if(isset($_POST['register']))
{

	include("connection.php");
	$user_email = $_POST['user_email'];
	$user_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
	$user_name = $_POST['user_name'];
	$user_status = 'Disabled';

	print_r($_FILES['file']);

	$image_dir = "images/";   // set image directory
	$user_profile = $image_dir.basename($_FILES['file']["name"]);
	move_uploaded_file($_FILES['file']["tmp_name"],$user_profile);  // set image attributes
	
	$user_created_on = date("Y-m-d h:i:s");	
	$query = "SELECT user_email FROM chat_user WHERE user_email = $user_email";
	$execute = mysqli_query($connection,$query);
	$rows = 0;
	if($execute) 
		$rows = mysqli_num_rows($execute);

	if($rows > 0)
	{
		echo "<script>window.alert('Email Already Exists.')</script>";
	}
	else
	{
		$user_status = "Enabled";
		$query = "INSERT INTO chat_user
		(user_name,user_password,user_email,user_profile,user_status,user_created_on)
		values('$user_name','$user_password','$user_email','$user_profile','$user_status','$user_created_on')";
		$execute = mysqli_query($connection,$query);
		if(isset($execute))
		{
			echo "<script>window.alert('User registered Successfully.')</script>";
			echo "<script>window.open('login.php','_self')</script>";
		}
		else
		{
			echo "<script>window.alert('Failed to Register.Please try again.')</script>";
			echo "<script>window.open('register.php','_self')</script>";
		}
		
	}
	
}

?>