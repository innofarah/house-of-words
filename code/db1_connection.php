<?php

$db = mysqli_connect("localhost", "root", "");
if($db){
	mysqli_select_db($db,'houseofwords');
	if (isset($_POST['logout'])) {
        $_SESSION['signed_out'] = true;
        header("location:login.php");
    }
	else if(isset($_POST['register'])){
		$user_exists = false;
		$email_exists = false;
		$search_user_query = "select user_name,email from user where user_name='".$_POST['username']."' or email='".$_POST['email']."'";
		$user_output = mysqli_query($db,$search_user_query);
		while($line = mysqli_fetch_assoc($user_output)){
			if($line['user_name'] == $_POST['username']){
				$user_exists = true;
				break;
			}
			else if($line['email'] == $_POST['email']){
				$email_exists = true;
				break;
			}
		}
			
		if(!$user_exists && !$email_exists) {
			if($_POST['gender'] == "M") $image = 'male.png';
			else if($_POST['gender'] == "F") $image = 'female.jpg';
			$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$register_query = "INSERT INTO `user` (`user_id`, `user_name`, `password`, `email`, `first_name`, `last_name`, `gender`, `country_code`, `about_me`, `image`,`incorrect_times`) VALUES (NULL,'".$_POST['username']."','".$password_hash."','".$_POST['email']."','".$_POST['fname']."','".$_POST['lname']."','".$_POST['gender']."','".$_POST['country']."',NULL,'".$image."',0);";
			if(mysqli_query($db,$register_query)){
				$_SESSION['username'] = $_POST['username'];
				header("location:page1.php");
			}
			else echo "error has occured ";
		}
	//	if(mysql_errno()==1062){        //error for duplicate username
		else if($user_exists)
			echo "This Username already exists. Choose another one.";
		else if($email_exists)
			echo "This email is already used. Choose another one";
	}
	else if(isset($_POST['login'])) {
		$login_query = "select user_id,user_name,password,email,image,incorrect_times from user where user_name=?;";
		
		$login_stmt = mysqli_prepare($db, $login_query);
		if(mysqli_stmt_bind_param($login_stmt,'s',$_POST['username'])>0){
			mysqli_stmt_bind_result($login_stmt, $user_id,$username, $password,$email,$image,$incorrect_times);
			if(mysqli_stmt_execute($login_stmt)){
				mysqli_stmt_store_result($login_stmt);
				mysqli_stmt_fetch($login_stmt);
				if( mysqli_stmt_num_rows($login_stmt)==1){
					$_SESSION['user_found'] = true;
					$line = mysqli_fetch_assoc($output);
					$_SESSION['image'] = $image;
					if($incorrect_times<5){
						if(password_verify($_POST['password'], $password)) {
							$_SESSION['password_correct'] = true;
							$_SESSION['username'] = $_POST['username'];
							$_SESSION['userid'] = $user_id;
							$correctpassword_query = "update user set incorrect_times=0 where user_name='".$_POST['username']."'";
							mysqli_query($db,$correctpassword_query);
							header("location:page1.php");	
						}
						else {
							$_SESSION['password_correct'] = false;
							$wrongpassword_query = "update user set incorrect_times=incorrect_times+1 where user_name='".$_POST['username']."'";
							mysqli_query($db,$wrongpassword_query);
						}	
					}
					else {
						$_SESSION['blocked_email'] = $email;
						//unset($_SESSION['password_correct']);  //?????
						header("location:contactus.php");
					}
				}
				else $_SESSION['user_found'] = false;
			}
		}
		
			
		
	}
	
	/*else if(isset($_POST['unblock'])) {
		$search_blocked_query="select email, incorrect_times from user where  email='".$_POST['email']."'";
		$search_blocked_output = mysql_query($search_blocked_query);
		if(mysql_num_rows($search_blocked_output)==1){
			$_SESSION['email_exist'] = true;
			$_SESSION['email'] = $_POST['email'];
		}
		else if(mysql_num_rows($search_blocked_output)==0) {
					$_SESSION['email_exist'] = false;
					header("location:contactus.php");
		}
	}*/
	

//	else if(isset($_POST['logout'])) {
//		unset($_SESSION['username']);
//		header("location:home.php");
//	}
}

?>