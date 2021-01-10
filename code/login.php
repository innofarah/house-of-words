<?php
	session_start();
	//echo $_SESSION['username'];
	if(isset($_SESSION['signed_out'])){
		echo "<h4 style='color:white; float:right;margin-right:20%'>You are signed out</h4>";
		//unset($_SESSION['username']);
		//session_destroy();
		$_SESSION=array();
	}
	if(isset($_POST['login']) && isset($_POST['rememberme'])) {	
		$time = time() + 3600 * 24 * 30; 
		setcookie('username', $_POST['username'], $time);
        setcookie('password', $_POST['password'], $time);
	}
	else if(isset($_POST['login']) && isset($_COOKIE['username'])) {
		setcookie('username', $_POST['username'], 1);
   		setcookie('password', $_POST['password'], 1);
	}
	//if(session_id() == "")
	//		session_start();
	if(isset($_SESSION['username']))
			header("location:page1.php");
		include 'db_connection.php';

?>
<html>
	<head>
		<title>House Of Words. Login</title>
	</head>
	<style>
		body, h1, h5{
			font-family: "Book Antiqua";
		}
		body, html{
			height: 100%;
		}
		body{ 
    		background-image: url('BdEayK-01.jpeg');
			opacity:0.7;
		    min-height: 100%;
			position:relative;
			scrolling : none;
		    background-position: center;
		    background-size: cover;
		}
		h5{
			margin-left:5%;
			margin-top: 1px;
			font-size: 20px;
			font-style: oblique;	
			font-family: "Book Antiqua";

 		}
		h3{
			margin-left:5%;
			font-size: 50px;	
			font-family: "Book Antiqua";
 		}
		.button{
			padding-left:25%;
			border-radius: 5px;
		    background-color: #ba9c6a;
		    color: black;
		    padding: 10px 0px;
		    margin: 8px 0;
		    border: none;
		    cursor: pointer;
		    width: 100%;		
		}
		.all{
			background-color:#f9c25f;
			opacity: 0.5;		
			width:40%;
			margin-left:15%;
			border-radius:2%;
			padding:8% 5% ;
		}
		form{ 
			margin-left:5%;
		}
		a{
			color:#691b17;
		}	
		.header{
			font-style:bold; 
			margin-left:5%;
		}
		.position{
			margin-left:55%;
		}
	</style>
	<body>
		<?php
		
		?>
		<div class="all">
			<h3>House Of Words</h3>
			<h5>A place where you can share your love of reading with the world.</h5>
			<div class="header"> Sign In</div>
			<form name="login_form" method="POST" action="login.php">
				<table>
					<tr>
						<td><label>Username: </label></td>
						<td><input type="text"   name="username" style="background-color:#e7d8cb" placeholder="Enter Username" required='true' <?php if(isset($_POST['username'])){ echo "value='".$_POST['username']."' ";} if(isset($_COOKIE['username'])) echo "value='".$_COOKIE['username']."' "; ?>></td>
					</tr>
					<tr>	
						<td><label>Password: </label></td>
						<td><input type="Password"  name="password" style="background-color:#e7d8cb" placeholder="Enter Password" required='true' <?php if(isset($_POST['password'])){ echo "value='".$_POST['password']."'";} if(isset($_COOKIE['password'])) echo "value='".$_COOKIE['password']."' "; ?>></td>
					</tr>
					<tr>
						<td><a href="forgetpassword.php" name="forgetpassword">forget password?</a> </td>
						<td><input type="checkbox" value="rememberme" name="rememberme">Remember me</input></td>
					</tr>
					<tr>
						<td><input type="submit" class="button" name="login" value="Sign In"></td>
						<td><a href="register.php">New?Sign up here!</a></td>
					</tr>		
				</table>
			<!--<a class="position" href="contactus.php">Contact us</a></div>-->
			</form>
		</div>

	<?php
		if(isset($_POST['login']) && isset($_SESSION['combination']) && !$_SESSION['combination']) echo "<h5 style='color:white'>Wrong username/password combination.</h5>";
		//else if(isset($_POST['login']) && isset($_SESSION['password_correct']) && !$_SESSION['password_correct']){
		//	echo "<h5 style='color:white'>The entered password is incorrect. Write it carefully.</h5>"; 
	//		$_SESSION['not_logged_in_user'] = $_POST['username'];
	//	}
	?>
	</body>
</html>	