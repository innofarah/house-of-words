<head>
    <link rel="stylesheet" type="text/css" href="reading_challenge_widget_stylesheet.css"></head>
    <style type="text/css">
    	.widget{
    		margin-top: 20px;
    		width:200px;
    		padding: 10px;
    		background-color:rgba(249,194,95,.1);
    		color: white;
    	}
    </style>

<?php

//include 'db_connection.php';
//session_start();

function create_widget($db, $user_id, $year){
	$find_challenge_query = "select * from reading_challenge where user_id=$user_id and year=$year";
	$find_challenge_output = mysqli_query($db, $find_challenge_query);

	if(mysqli_num_rows($find_challenge_output)==1){
		echo "<div class='widget' >";
		$line = mysqli_fetch_assoc($find_challenge_output);
		$number_of_challenge_books = $line['number_of_books'];
		$books_query = "select * from added_book, book where book.book_id=added_book.book_id and user_id=".$user_id." and state=0 and YEAR(timestamp)=".$year;
		$books_output = mysqli_query($db, $books_query);
		$number_of_read_books = mysqli_num_rows($books_output);
		$percentage = (int)(($number_of_read_books/$number_of_challenge_books)*100);
		//echo $percentage;

       
        if($user_id == $_SESSION['userid']){
        	$text = "Your";
        	$text1 = "You've";
        }
        else{
        	$user_query = "select first_name from user where user_id=$user_id";
        	$user_output = mysqli_query($db, $user_query);
        	$line = mysqli_fetch_assoc($user_output);
        	$text = $line['first_name']."'s";
        	$text1 = $text." has";
        }
        echo "<h4>$text $year Reading Challenge</h4>";
        echo "<h5>$text1 read $number_of_read_books/$number_of_challenge_books books</br></br>";
       // if($percentage>100) echo "Done!</br>";
         echo "<progress value='".$percentage."'' max='100' style='height: 20px; width: 100px'></progress>&nbsp&nbsp&nbsp<span style='color:rgba(116, 164, 242,0.8);'>".$percentage."%</span></br></br>";
         $i=0;
         echo "<table border='0' cellspacing='0' cellpadding='0'><tr>";
         while($line=mysqli_fetch_assoc($books_output)){
			if($i<3){
				$book_name = $line['book_name'];
 				$href = "book.php?book_id=".$line['book_id'];
 				echo "<td width='30px' valign='top' ><a href='".$href."'><img src='book_pics/".$line['book_image']."'  style='object-fit: cover; width:30px; height:auto;'></a></td>";
			}
			$i++;
		}
		echo "</tr></table>";
		if($_SESSION['see_more_flag']==0){
			$href = "challenges_by_year.php";
		}
		else{
			$href = "single_challenge.php?userid=$user_id&year=$year";
		}
		echo "<a href=$href>see more..</a></br>";
		if($user_id==$_SESSION['userid']) echo "<a href='set_challenge.php'>Edit Challenge</a>";
		echo "</div>";
	}

	else if($user_id==$_SESSION['userid']){   //if challenge is not set
		echo "<div class='widget' >";
		echo "<h4>Your Reading Challenge</h4>";
		echo "<p style='font-size:20px'>You didn't set a challenge for this year!</p>";
		if($year == date("Y")){
			echo "<a style='font-size:15px' href='set_challenge.php'>Create a challenge and beat yourself!</a>";
		}
		echo "</div>";
	}
	
}

//create_widget($db, 28, 2019);



?>