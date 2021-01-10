<html>
	<head>
		<style>
			body{ 
			    background-image: url('BdEayK-01.jpeg');
				opacity:0.7;
			    min-height: 100%;
				position:relative;
				scrolling : none;
			    background-position: center;
			    background-size: cover;
			}
			.all{
				background-color:#f9c25f;
				opacity: 0.6;		
				width:20%;
				margin-left:15%;
				border-radius:5%;
				padding:8% 5% ;
			}
		</style>
	</head>

	<body>
		<?php
			session_start();
			if(!isset($_SESSION['blocked_email'])) header("location:login.php");
			//include 'db_connection.php';
		?>
		<form class='all'  method='post' action='verifycontactus.php'>
			<p style="color:white"> You are blocked. Press the button below if you want to be unblocked </p>
			<input type="submit"  name="unblock" value="unblock"></input>
		</form>
	</body>
</html>