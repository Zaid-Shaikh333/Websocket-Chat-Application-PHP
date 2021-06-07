<?php
session_start();

if(!isset($_SESSION['user']))
{
	session_destroy();
	header("location:login.php");
}
/*
User Session Array
0 = user_name
1 = user_password
2 = user_email
3 = user_profile
4 = user_login_status
5 = token
*/
$user_details = $_SESSION['user']; 

?>
<!DOCTYPE html>
<html>
<head>
	<title>Chatroom</title>
	<link rel="stylesheet" type="text/css" href="css/register.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/f64eb30908.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

</head>
<body>
	<div class="container">
		<h1 class="text-center">Welcome to Socketing</h1>
		<br>
		<div class="row">
			<div class="col-lg-8">
				<div class="message-body">
					<h3 id="receiver" style="position: sticky;">Chat here</h3>
					<div class="message-area" id="message-area" style="overflow-y: auto;overflow-x: hidden; margin: 0.75rem 0.1rem;">
						
					</div>
				</div>
				<div class="send-message">
					<form method="post" id="chat_form">
						<input type="text" name="type_msg" id="chat_message" placeholder="Type message here..." required="">
						<button type="submit" name="send_msg" id="send"><i class="fas fa-paper-plane"></i></button>
					</form>
				</div>
			</div>
			<div class="col-lg-4">
				<input type="hidden" name="to_user_email" id="to_user_email" value=""> 
				<input type="hidden" name="to_user_name" id="to_user_name" value="">
				<input type="hidden" name="login_user_email" id="login_user_email" value="<?php echo $user_details[2] ; ?>">
				<input type="hidden" name="login_user_name" id="login_user_name" value="<?php echo $user_details[0] ; ?>">
				<div class="mt-3 mb-3 text-center">
					<img src="<?php echo $user_details[3]; ?>" width='200' height='200' class='img-fluid rounded-circle img-thumbnail'>
					<h3 class="mt-2"><?php echo $user_details[0]; ?></h3>
					<a href="edit_profile.php" class="btn btn-outline-success mt-2 mb-2 editprofile">Edit Profile</a>
					<a href="logout.php?id=<?=base64_encode($user_details[2]);?>" class="btn btn-outline-dark ml-1 mt-2 mb-2 editprofile">Logout</a>
				</div>
				<?php

				include("functions.php");

				$token = $user_details[5];
				// Get all Users except the Logged user
				$users_list = get_users_list($user_details[2]);

				?>
				<table class="table">
					<thead class="thead-style">
						<tr>
							<td class="text-center">Users List</td>
							<td class="text-center">Login Status</td>
							<td class="text-center"></td>
						</tr>
					</thead>
					<tbody>
						<?php
							while($fetch = mysqli_fetch_assoc($users_list))
							{
						?>
						<tr class="table-row-users">
							<td class="text-center" id="<?php echo $fetch['user_name']; ?>" value="<?php echo $fetch['user_name']; ?>">
								<?php echo $fetch['user_name']; ?></td>
							<td class="text-center"><?php echo $fetch['user_login_status']; ?></td>
							<td class="text-center"><button class="btn btn-outline-primary receiver_email" id="single_chat" data='<?php echo $fetch["user_email"];?>' onclick='setReceiver("<?php echo $fetch['user_name']; ?>","<?php echo $fetch["user_email"];?>")'>Chat</button></td>
						</tr>
						<?php 
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>
<script type="text/javascript">
	function setReceiver(receiver_name,receiver_email)
		{
			var to_user_name = document.getElementById(receiver_name).innerHTML;
			//console.log(to_user_name);
			/*
			var node = document.createElement("span"); 
			node.className = "receiver_heading";
			var textnode = document.createTextNode("with "+to_user_name);
			node.appendChild(textnode);
			document.getElementById("receiver").appendChild(node);
			textnode = '';
			*/
			document.getElementById('receiver').innerHTML = "Chat here with "+to_user_name;

			document.getElementById('to_user_email').value = receiver_email;

			document.getElementById('to_user_name').value = receiver_name;
		}

	$(document).ready(function(){
		var conn = new WebSocket('ws://localhost:8080?token=<?php echo $token; ?>');
		conn.onopen = function(e) {
		    console.log("Connection established!");
		    
		};

		conn.onmessage = function(e) {
		    console.log(e.data);

		    var data = JSON.parse(e.data);
		    console.log(data['from']);
		    var row_class = '';
		    var background_class = '';
		    var user = '';
		    //var time = '<?php echo date( 'd-m-Y h:i:s',time()); ?>';
		    var from_name = $('#login_user_name').val();
		    
		    if(data.from == from_name)
		    {
		    	row_class = 'row justify-content-end mr-auto right-space';
		    	background_class = 'modal-right';
		    	user = data['from'];
		    }
		    else
		    {
		    	row_class = 'row justify-content-start';
		    	background_class = 'text-dark alert-light';
		    	user = data['from'];
		    }


		    var html_data = "<div class='"+row_class+"'><div class='col-sm-10'><div class='shadow-box custom-alert "+background_class+"'><b>"+user+" - </b>"+data.msg+"<br/><div class='text-right'><small><i>"+data.time+"</i></small></div></div></div></div>";

		    $('#message-area').append(html_data);
		    $('#message-area').scrollTop($('#message-area')[0].scrollHeight);
		    $('#chat_message').val("");
		};

		$('#chat_form').on('submit', function(event){

			event.preventDefault();

			var from_email = $('#login_user_email').val();
			var message = $('#chat_message').val();
			var from_name = $('#login_user_name').val();
			var to_email = $('#to_user_email').val();
			var to_name = $('#to_user_name').val();

			//console.log(to_name);
			var data = {
				sender_email : from_email,
				receiver_email : to_email,
				sender_name : from_name,
				receiver_name : to_name,
				msg : message,
				type : 'Private',
				time : ''
			};

			conn.send(JSON.stringify(data));

		});

		var receiver_email = '';
		
		$(document).on('click','#single_chat',function(){

			$('#message-area').html('');

			var message_sender = $('#login_user_email').val();
			var message_receiver = $(this).attr("data");
			//console.log(message_sender);
			//console.log(message_receiver);

			$.ajax({
				url:"load_chats.php",
				type:"post",
				data:{action:'fetch_chat',to_user:message_receiver,from_user:message_sender},
				dataType:"JSON",
				success:function(data)
				{
	
							var row_class = '';
		    				var background_class = '';
		    				var from = '';

		    				if(data[count].from_user == message_sender)
		    				{
		    					row_class = 'row justify-content-end';
		    					background_class = 'text-dark alert-light';
		    					from = 'Me';
		    				}
		    				else
		    				{
		    					row_class = 'row justify-content-start';
		    					background_class = 'text-dark alert-warning';
		    					from = 'Other';
		    				}

		    				var html_data = "<div class='"+row_class+"'><div class='col-sm-10'><div class='shadow-box alert "+background_class+"'><b>"+data.user_name+" - </b>"+data[count].chat_message+"<br/><div class='text-right'><small><i>"+data[count].timestamp+"</i></small></div></div></div></div>";

		    				$('#message-area').append(html_data);
							$('#chat_message').val("");
				}
			});
			message_receiver = '';
			message_sender = '';
			$('html, body').animate({
	        	scrollTop: $("#receiver").offset().top}, 200);
		});

	})
		

</script>

</html>