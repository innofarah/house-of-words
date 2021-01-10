<?php

include "db_connection.php";
include "functions.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php

 if(isset($_GET['user_id']) && isset($_GET['update_id']) && isset($_GET['insertcomment'])){
 	$user_id = $_GET['user_id'];
 	$update_id = $_GET['update_id'];
 	$comment_txt = $_GET['comment_txt'];
 	$comment_txt = mysqli_real_escape_string($db, $comment_txt); //for char like '
 	//echo $comment_txt;
 	$insert_comment_query = "insert into comments values(null,$user_id,$update_id,'".$comment_txt."',CURRENT_TIMESTAMP)";
 	//echo $insert_comment_query;
 	
 	$result = mysqli_query($db, $insert_comment_query);

 	echo "<span id='comments_div'>";
 	display_comments($update_id,$db);
 }
 else if(isset($_GET['user_id']) && isset($_GET['update_id']) ){       //to insert like in the db and to load nblike
	$user_id = $_GET['user_id'];
	$update_id = $_GET['update_id'];	
	$user_like_exists_query = "select * from likes where user_id=$user_id and update_id=$update_id";
	$user_like_exists_output = mysqli_query($db, $user_like_exists_query);
	if(mysqli_num_rows($user_like_exists_output)==0) {
		$modify_like_query = "insert into likes values(null,$user_id,$update_id,CURRENT_TIMESTAMP)";
	}
	else $modify_like_query = "delete from likes where user_id=$user_id and update_id=$update_id";
		if(mysqli_query($db, $modify_like_query)){
			$num_likes_query = "select *,count(like_id) as num_likes from likes where update_id=$update_id group by update_id";
			$likes_output = mysqli_query($db, $num_likes_query);
			$line = mysqli_fetch_assoc($likes_output);
			if(mysqli_num_rows($likes_output)==0) $line = 0;
			else $line = $line['num_likes'];
			echo "<span id='numlikes'>$line likes.</span>";
		}
	
 }

 else if(isset($_GET['update_id'])){                       //to display likes in the modal
 	$update_id = $_GET['update_id'];
 	$likes_query = "select * from likes,user where likes.user_id=user.user_id and update_id=$update_id";
 	$likes_output = mysqli_query($db, $likes_query);
 	echo "<span id='likes_display'>";
 	while($line=mysqli_fetch_assoc($likes_output)){
 		$user_id = $line['user_id'];
 		$fname = $line['first_name'];
 		$lname = $line['last_name'];
 		$href = "friend_profile.php?userid=$user_id";
 		echo "<a href=$href>$fname $lname</a><hr>";
 	}
 	echo "</span>";
 }



/*if(isset($_GET['user_id']) && isset($_GET['update_id']) && isset($_GET['changeliketext'])){
		$user_like_exists_query = "select * from likes where user_id=$user_id and update_id=$update_id";
		$user_like_exists_output = mysql_query($user_like_exists_query);
		if(mysql_num_rows($user_like_exists_output)==1){
			$text = "Unlike";
		}
		else $text = "Like";
		echo "<span id='likelinktext'>$text</span>";
	}*/

?>

</body>
</html>