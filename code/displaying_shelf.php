<?php
include "db_connection.php";

if(isset($_GET['state']) && isset($_GET['user_id'])){
	$state = $_GET['state'];
	$user_id = $_GET['user_id'];
	$shelf_query = "select * from added_book,book where added_book.book_id=book.book_id and added_book.user_id = $user_id and added_book.state=$state";
	$shelf_output = mysqli_query($db, $shelf_query);
	echo "<div id='shelf'>";
	echo "<h3 style='color:maroon;'>";
		if($state==0)
			echo "Books marked as Read:";
		else if($state==1)
			echo "Books marked as Currently Reading:";
		else if($state==2)
			echo "Books marked as Want to Read:";
		echo "</h3>";
	while ($line=mysqli_fetch_assoc($shelf_output)) {
		$book_name = $line['book_name'];
 		$href = "book.php?book_id=".$line['book_id'];
 		echo "<table border='0' width='550px'><tr><td width='55px' valign='top'><img src='book_pics/".$line['book_image']."'  style='object-fit: cover; width:60px; height:auto;'></td>";
 		echo "<td><a href='".$href."' style='color:white; text-decoration:none;'>".$book_name."</a></td></table>";
	}
	echo "</div>";
}

?>