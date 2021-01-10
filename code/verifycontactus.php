<!DOCTYPE html>
<html>
	<head>
		<style>
			body { 
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
			if(isset($_SESSION['code_verified']) && !$_SESSION['code_verified']) echo "<h3 style='color:white'>code is not verified</h3>";
			if(isset($_POST['unblock'])){
				$code= floor(rand());
				$_SESSION['code']= $code;
				if(mail($_SESSION['blocked_email'],'Forget your password ?','kindly use the code '.$code.' to change your account password.' ,'From: websitesender7@gmail.com')){
	 				echo "<h3 style='color:white'>A code was sent to your email ".$_SESSION['blocked_email']."\n please enter the correct code to proceed.</h3>";
	 			}	
				else echo 'email not sending ,check ur internet connection' ;
			}
			else if(!isset($_SESSION['code_verified'])) header("location:login.php");
		?>

		<form class='all'  method='post' action='mail.php'>
			<label>Code: </label>	
			<input type="text" name="code" id="email"></input>
			<input type="submit"  name="unblock" value="unblock"></input>
		</form>
	</body>
</html>