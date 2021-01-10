<!DOCTYPE html>
<html>

<div id="search_results_div">
<?php
 include "db_connection.php";
 
 //if(isset($_GET['search'])){
 	$filter = $_GET['filter'];
 	if($filter==0){
 		$all_books_query = "select * from book";
 		$books_output = mysqli_query($db, $all_books_query);
 		while ($line = mysqli_fetch_assoc($books_output)) {
 			$result_string = "";
 			$result_string .= $line['book_name'].$line['description'];
 			$key = $_GET['key'];
 			$model = "([[:alnum:]] | [[:space:]])*$key([[:alnum:]] | [[:space:]])*";
 			if(@eregi($model, $result_string)){
 				$book_name = $line['book_name'];
 				$href = "book.php?book_id=".$line['book_id'];
 				echo "<table border='0' width='550px'><tr><td width='55px' valign='top'><img src='book_pics/".$line['book_image']."'  style='object-fit: cover; width:60px; height:auto;'></td>";
 				echo "<td><a href='".$href."' style='color:white; text-decoration:none;'>".$book_name."</a></td></table>";
 			}
 		}
 	}
 	else if($filter==1){
 		$all_users_query = "select * from user";
 		$users_output = mysqli_query($db, $all_users_query);
 		while($line = mysqli_fetch_assoc($users_output)){
 			$result_string = "";
 			$result_string .= $line['user_id'].$line['user_name'].$line['first_name'].$line['last_name'].$line['email'].$line['about_me'];
 			$key = $_GET['key'];
 			
 			$model = "([[:alnum:]])*$key([[:alnum:]])*";
 			if(@eregi($model, $result_string)){
 				$name = $line['first_name']." ".$line['last_name'];
 				$href = "friend_profile.php?userid=".$line['user_id'];
 				echo "<table border='0' width='550px'><tr><td width='55px' valign='top'><img src='user_profile_pics/".$line['image']."' style='object-fit: cover; width:50px; height:50px;'></td>";
 				echo "<td><a href='".$href."' style='color:white; text-decoration:none'>".$name."</a></td></table>";
 			}
 		}
 	}
 	else if($filter==2){
 		$all_authors_query = "select * from author";
 		$authors_output = mysqli_query($db, $all_authors_query);
 		while($line=mysqli_fetch_assoc($authors_output)){
 			$result_string = "";
 			$result_string .= $line['fname'].$line['lname'].$line['about'].$line['website'];
 			$key = $_GET['key'];
 			$model = "([[:alnum:]])*$key([[:alnum:]])*";
 			if(@eregi($model, $result_string)){
 				$name = $line['fname']." ".$line['lname'];
 				$href = "author.php?author_id=".$line['author_id'];
 				echo "<table border='0' width='550px'><tr><td width='55px' valign='top'><img src='author_pics/".$line['image']."' style='object-fit: cover; width:50px; height:50px;'></td>";
 				echo "<td><a href='".$href."' style='color:white; text-decoration:none'>".$name."</a></td></table>";
 			}
 		}
 	}
 //} continue for author and genre.
?>
</div>

</html>