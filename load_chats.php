<?php

session_start();

if (isset($_POST['action']) && $_POST['action'] == 'fetch_chat') {
	# code...
	require('functions.php');

	$sender = $_POST['from_user'];
	$receiver = $_POST['to_user'];

	include('database/connection.php');
	$query = "SELECT message,timestamp,to_user_email,from_user_email FROM chat_messages 
	WHERE (from_user_email = '$sender' AND to_user_email = '$receiver')
	OR (from_user_email = '$receiver' AND to_user_email = '$sender')
	ORDER BY timestamp";
	$execute = mysqli_query($conn,$query);
	if(isset($execute))
	{
		while($get_data = mysqli_fetch_assoc($execute))
		{}
	}

}
?>