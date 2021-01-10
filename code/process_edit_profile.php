<?php

include 'db_connection.php';
session_start();
if(isset($_POST['update'])){
	$userid = $_SESSION['userid'];
	$fname = $_POST['fname'];   //written
	$lname = $_POST['lname'];   //written
	$username = $_POST['username'];  //written
	$_SESSION['username'] = $username;
	$gender = $_POST['gender'];   //gender
	$country = $_POST['country'];   //written
	$DOB = $_POST['year']."-".$_POST['month']."-".$_POST['day'];   //written
	$interests_text = $_POST['interests'];
	$about_me = $_POST['about_me'];   //written
	$view_privacy = (int)$_POST['viewPriv']; //written
	if(isset($_POST['followPriv'])){
		$follow_privacy = 0;
	}
	else $follow_privacy = 1;
	
	$update_query = "update user set first_name=?, last_name=?, user_name=?, gender=?, country_code=?, DOB=?, about_me=?, view_privacy=?, follow_privacy=? where user_id=?";
	$stmt = $db->prepare($update_query);
	$stmt->bind_param('sssssssiii', $fname, $lname, $username, $gender, $country, $DOB, $about_me, $view_privacy, $follow_privacy,  $userid);
	$stmt->execute();
	$stmt->close();

	$pattern = "([a-z]|[0-9])+(,\s*([a-z]|[0-9])+)*";
	if(@ereg($pattern, $interests_text)){
		$interests_text = strtolower($interests_text); // "movies,Movies";
		@$interests = split(",", $interests_text);
		for($i=0; $i<sizeof($interests); $i++) {
			$interests[$i] = str_replace(' ', '', $interests[$i]);
		}
		$search_query = "select * from interests i, user_interest u, user us where u.user_id=us.user_id and u.interest_id=i.interest_id and u.user_id=$userid";
		$old_interests = [];
		$search_output = mysqli_query($db, $search_query);
		while($line = mysqli_fetch_assoc($search_output)){
			$name = str_replace(' ','', $line['interest_name']);
			array_push($old_interests, $name);
		}
		//$intersection = array_intersect($interests, $old_interests);
		$result_new = array_diff($interests, $old_interests);
		$result_new = array_values($result_new);
		$result_old = array_diff($old_interests, $interests);

		//add the new interests to interests if they don't already exist, and then add them to user_interest.
		
		//delete the old interests from user_interest.

		if(sizeof($result_new)>0){
	  		$existing_interests = [];
			//$interests_ids = [];
			$len = sizeof($result_new);
			$search_query = "select interest_name,interest_id from interests where interest_name in (";
			for($i=0; $i<$len-1; $i++){
			$search_query .= "?,";
			}
			$search_query .= "?)";
			$stmt = $db->prepare($search_query);
			$types = str_repeat('s',$len);
			$stmt->bind_param($types, ...$result_new);
			if($stmt->execute()){
   		 	//echo mysqli_error($db);
   			 	$stmt->bind_result($name, $id);
   			 	while ($row = $stmt->fetch()) {
					array_push($existing_interests, $name);
					//array_push($interests_ids, $id);
				}
				$new_interests_to_insert = array_diff($result_new, $existing_interests);
				$new_interests_to_insert = array_values($new_interests_to_insert); //renumber indexes
				if(sizeof($new_interests_to_insert)>0){
					$insert_query = "insert into interests (interest_id,interest_name) values(null,?)";
					$stmt = $db->prepare($insert_query);
					$par = "";
					$stmt->bind_param('s',$par);
					for($i=0;$i<sizeof($new_interests_to_insert);$i++){
						$par = $new_interests_to_insert[$i];
						$stmt->execute();
					}
				
				}



			$interests_ids = [];
			$len = sizeof($result_new);
			$search_query = "select interest_name,interest_id from interests where interest_name in (";
			for($i=0; $i<$len-1; $i++){
			$search_query .= "?,";
			}
			$search_query .= "?)";
			$stmt = $db->prepare($search_query);
			$types = str_repeat('s',$len);
			$stmt->bind_param($types, ...$result_new);
			if($stmt->execute()){
   		 	//echo mysqli_error($db);
   			 	$stmt->bind_result($name, $id);
   			 	while ($row = $stmt->fetch()) {
					array_push($interests_ids, $id);
				}


				$insert_query2 = "insert into user_interest (user_interest_id,user_id,interest_id) values(null,$userid,?)";
				$stmt = $db->prepare($insert_query2);
				$par = 1;
				$stmt->bind_param('i', $par);
				for($i=0;$i<sizeof($interests_ids);$i++){
					$par = $interests_ids[$i];
					$stmt->execute();
				}
			}
			
			

		}


	}
	}
	
	if(!empty($_FILES['pic']['name'])){
          $location = "C:\Data\php\HouseOfWords4\user_profile_pics";
          $pic = $_FILES['pic'];
          if($pic || ($pic!="none")){
            $tmp_pic = $pic['tmp_name'];
            if($pic['size'] <= 10240000){
              $extension = explode("/", $pic['type']);
              $pic_def = $location."/".$username.".".$extension[1];
              move_uploaded_file($tmp_pic, $pic_def);
              $update_pic_query = "update user set image='".$username.".".$extension[1]."' where user_name='".$username."'";
            //  echo $update_pic_query;
              mysqli_query($db, $update_pic_query); 
              echo "ok";
             // header("Refresh:0");
            } 
          }
        }
	

}

//fix if for ex "movies" and "Movies"

header("location:profile.php");

?>
