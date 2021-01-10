<?php
	session_start();
	if(isset($_SESSION['username']))
		header("location:page1.php");
?>
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
		<form id='myform' class='all' action='mail.php' method='post'>
			<p>enter your email</p>
			<input type='text' name='email'></input>
			<input type='submit' name='forget' value='send' ></input>
		</form>
	</body>
</html>