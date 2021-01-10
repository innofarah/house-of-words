<html>
  <head>
    <title>House Of Words. Login</title>
    <?php session_start();
          $_SESSION['updates_div_counter'] = 0;
          $_SESSION['see_more_flag'] = 1;
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


     function load_challenges1(val){
     var url = "loadchallenges.php?year=" + val;
    // alert(url);
      $("#challenges_view").load(url, function(){});
     
    }
    var val = new Date().getFullYear();
    var url = "loadchallenges.php?year=" + val;
    $(document).ready(function(){
      $("#challenges_view").load(url, function(){})
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

        echo "<h2 style='color:maroon'>Challenges by Year</h2>";

        ?>
       
        <select name="year" id="yearlist" onchange="load_challenges1(this.value)">
              <option value=" "> </option>
              <?php
                $current_year = date("Y");
                for($i=1900; $i<=$current_year; $i++){
                  echo "<option value='".$i."' ";
                  if($i == $current_year)
                    echo " selected";
                  echo ">".$i."</option>";
                }
              ?>
            </select>

            <div id='challenges_view'> </div>
           


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


  </body>
</html>

