<?php

session_start();
if(isset($_GET['id']))
{
	$encoded = $_GET['id'];
	$user_email = base64_decode($encoded);
	
	include('functions.php');

	$result = set_user_offline($user_email);

	if($result == "Offline")
	{
		echo "<script>window.alert('User Logged Out.')</script>";
		session_destroy();
		echo "<script>window.open('login.php','_self')</script>";
	}
}

?>