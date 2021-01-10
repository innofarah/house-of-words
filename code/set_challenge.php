<html>
  <head>
    <title>House Of Words. Login</title>
    <?php session_start();
          $_SESSION['updates_div_counter'] = 0;
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
        <?php create_widget($db, $_SESSION['userid'], date("Y")); ?> 
      </div>
      
      <div class="book" id='updatesdiv'>
        <?php

        echo "<h3 style='color:maroon'>".date("Y")." Reading Challenge</h3>";
        echo "I want to read: ";
        echo "<input type='text' name='nbooks'> books.</br></br></br>";
        echo "<input type='submit' class='button' style='font-size:20px' name='setchallenge' value='Set Challenge!'>";

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
      <!--<input type="button" name="search" value="Search" onclick="display_search()">
      <br/> -->

      
       <!-- <?php 
      // $rowlimit = $_SESSION['rowlimit'];
      //  $i = $_SESSION['i'];
      //  $j = $_SESSION['j'];
       // $k = $_SESSION['k'];
        //$updates_div_counter = $_SESSION['updates_div_counter'];
       // echo "$rowlimit, \"$ids\", $user_id, $i, $j, $k, $updates_div_counter";?> -->
      <div id="search_result"></div>
    </form>
    </div>


    <?php

    if(isset($_POST['setchallenge']) && isset($_POST['nbooks'])){
    	$user_id=$_SESSION['userid'];
    	$year = date("Y");
    	$find_challenge_query = "select * from reading_challenge where user_id=$user_id and year=$year";
		$find_challenge_output = mysqli_query($db, $find_challenge_query);
		if(mysqli_num_rows($find_challenge_output)==0){
    		$insert_challenge_query = "insert into reading_challenge values(null,".$_SESSION['userid'].",".$_POST['nbooks'].",".date("Y").")";
    		mysqli_query($db, $insert_challenge_query);
    		header("location:page1.php");
   		}
   		else {
   			$update_challenge_query = "update reading_challenge set number_of_books=".$_POST['nbooks']." where user_id=".$_SESSION['userid']." and year=".date("Y");
   			mysqli_query($db, $update_challenge_query);

   		}
   	}

    ?>


  </body>
</html>

