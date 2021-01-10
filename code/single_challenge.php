<html>
  <head>
    <title>House Of Words. Login</title>
    <?php session_start();
          $_SESSION['updates_div_counter'] = 0;
          //$_SESSION['see_more_flag'] = 1;
         // session_destroy();
        // print_r($_SESSION);
          include "db_connection.php";
          include "functions.php";
          include 'reading_challenge_widget.php';
          
          if(!isset($_SESSION['username'])) header("location:login.php");
         ?>
  <script src="jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="functions_jscript.js"></script>
 <!-- <script type="text/javascript" src="functions_jscript3.js"></script> -->
  <script type="text/javascript">;
   // generate_updates_action(0, "'27'x'28'", 19, 0, 0, 0, 0);
    document.addEventListener("keypress",function(event){ 
      if(event.keyCode==13)
          event.preventDefault();
      });

  </script>
  
  <link rel = "stylesheet" type = "text/css" href = "page1_stylesheet.css" />
  <style type="text/css">  a {
      color: white;
      text-decoration: none;
    }
    
    a:visited{
      color: white;
      text-decoration: none;
    }

    .modal a{
      color: black;
    }

</style>
  </head>
  <body>
    <form name="logout_form" action="set_challenge.php" method="POST">
      <div class="sidenav">
        <a href="page1.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="friends.php">Friends</a>
        <a href="requests.php" id='requests'>Friend Requests</a>
        <input type="submit" class="button" name="logout" value="Sign Out">
        
      </div>
      
      <div class="book" id="updatesdiv">

        <?php

        $user_id = $_GET['userid'];
        $year = $_GET['year'];
        $user_query = "select * from user where user_id=$user_id";
          $user_output = mysqli_query($db, $user_query);
          $line = mysqli_fetch_assoc($user_output);

        if($user_id==$_SESSION['userid']){
          echo "<h2 style='color:maroon'>Your Reading Challenge For $year</h2>";
        }
        else{
          echo "<h2 style='color:maroon'>".$line['first_name']."'s Reading Challenge For $year";
        }
      echo "</h2></br>";
      $find_challenge_query = "select * from reading_challenge where user_id=$user_id and year=$year";
     $find_challenge_output = mysqli_query($db, $find_challenge_query);
     if(mysqli_num_rows($find_challenge_output)==1){
        $line = mysqli_fetch_assoc($find_challenge_output);
       $number_of_challenge_books = $line['number_of_books'];
     }
       $books_query = "select * from added_book, book where book.book_id=added_book.book_id and user_id=".$user_id." and state=0 and YEAR(timestamp)=".$year;
        $books_output = mysqli_query($db, $books_query);
        $number_of_read_books = mysqli_num_rows($books_output);
      $percentage = (int)(($number_of_read_books/$number_of_challenge_books)*100);
        echo "<h3 style='color:white'>Progress: $number_of_read_books/$number_of_challenge_books books</br></br>";
         echo "<progress value='".$percentage."'' max='100' style='height: 20px; width: 100px'></progress>&nbsp&nbsp&nbsp<span style='color:rgba(116, 164, 242,0.8);'>".$percentage."%</span></br></br>";

        while ($line = mysqli_fetch_assoc($books_output)) {
          $result_string = "";
          $result_string .= $line['book_name'].$line['description'];
        $book_name = $line['book_name'];
        $href = "book.php?book_id=".$line['book_id'];
        echo "<table border='0' width='550px'><tr><td width='55px' valign='top'><img src='book_pics/".$line['book_image']."'  style='object-fit: cover; width:60px; height:auto;'></td>";
        echo "<td><a href='".$href."' style='color:white; text-decoration:none;'>".$book_name."</a></td></table>";
      
    }

        ?>
       
          

      </div>
    </form>
    <div class="search_div">
    <form name='search_form' method="post" action="" id='search_form'>
      <h3 style='color: white;'>Discover</h3>
      <input type="text" name="search_key" onchange="display_search()" onfocus="display_search()" oninput="display_search()" autocomplete="off"> <!--onchange happens only after losing focus -->
      <select name="filter" onchange="display_search()">
        <option value='0'>Book</option>
        <option value="1">User</option>
        <option value="2">Author</option>
        <option value="3">Genre</option>
      </select>
      <br/>

      <div id="search_result"></div>
    </form>
    </div>


  </body>
</html>

