<?php


if(isset($_POST['register']))
{
	session_start();

	if(isset($_SESSION['user_data']))
	{
		header("location:chatroom.php");
	}
	session_destroy();
	
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="css/register.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/f64eb30908.js" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
		<div class="row justify-content-center">
			<div class="card text-center align-middle">

				<h5 class="card-title">Register for some Socketing</h5>
				<form action="add_user.php" enctype="multipart/form-data" method="post">
					<div class="form-group">
						<i class="far fa-envelope"></i>
						<input type="email" name="user_email" required="" id="user_email" placeholder="Enter your Email">
					</div>
					<div class="form-group">
						<i class="fas fa-lock"></i>
						<input type="password" name="user_password" required="" id="user_password" placeholder="Enter Password">
					</div>
					<div class="form-group">
						<i class="far fa-user"></i>
						<input type="text" name="user_name" required="" id="user_name" placeholder="Enter your Name">
					</div>
					<div class="form-group">
 						<i class="fas fa-file-upload">
 						<input class="image-upload" type="file" name="file" placeholder="Upload profile pic" required=""></i>
 					</div>
					<div class="form-group">
						<button type="submit" name="register" class="btn btn-outline-primary">Register</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>