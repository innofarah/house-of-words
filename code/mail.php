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
			if(!isset($_POST['forget']) && !isset($_POST['change']) && !isset($_POST['unblock']))
				header("location:page1.php");
			include 'db_connection.php';
			$code= floor(rand());
			if(isset($_POST['forget'])){
				$_SESSION['email'] = $_POST['email'];
				$_SESSION['code']= $code;
				if(mail($_POST['email'],'Forget your password ?','kindly use the code '.$code.' to change your account password.' ,'From: websitesender7@gmail.com')){
		 				echo "email sent ";
		 		}	
		 		else echo 'email not sending ,check ur internet connection' ;
			}
			else if(isset($_POST['change']) && $_POST['code'] == $_SESSION['code']) {
				$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);	
				$update_password_query = "update user set password='".$password_hash."' where email='".$_SESSION['email']."'";
				mysqli_query($db, $update_password_query); 
				$_SESSION['password_changed'] = true;
	    		header("location:loading.php");
			}
			else if(isset($_POST['change']) && $_POST['code'] != $_SESSION['code']){
				echo "code is not correct, couldn't change password";
			}
			if(isset($_POST['unblock']) && isset($_POST['code']) && $_POST['code'] == $_SESSION['code']){
				$unblock_user_query="update user set incorrect_times = 0 where  email ='".$_SESSION['blocked_email']."'";
				mysqli_query($db, $unblock_user_query);	
				$_SESSION['code_verified'] = true;
				header("location:loading.php");
			}
			else if(isset($_POST['unblock']) && isset($_POST['code']) && $_POST['code'] != $_SESSION['code'] ){
				$_SESSION['code_verified']=false;  
				header("location:verifycontactus.php");
			}
		?>
		<form class='all' action='mail.php' method='post' id='form1'>
			<input type='text' name='code' placeholder='enter your code'></input>
			<input type='password' name='password' placeholder='enter new password'></input>
			<input type='submit' name='change' value='change'></input>
		</form>
	</body>
</html>

