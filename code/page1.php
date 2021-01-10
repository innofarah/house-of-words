<html>
  <head>
    <title>House Of Words. Login</title>
    <?php session_start();
          $_SESSION['updates_div_counter'] = 0;
          $_SESSION['see_more_flag'] = 0; //for see more link of challenge
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

    var timeLastProcessed = new Date().getTime() + 1000;
    var timeNow = 0;
    var timeInterval = 5000;
    var begin = true;
    function scrolled(o)
        {          
            timeNow = new Date().getTime();

            //alert(timeNow);
            //visible height + pixel scrolled = total height 
            if(o.offsetHeight + o.scrollTop >= o.scrollHeight)
            {
                //alert("offset height " + o.offsetHeight);
               // alert("scrolltop " + o.scrollTop);
               // alert("scrollHeight " + o.scrollHeight);
               if(!begin && timeNow - timeLastProcessed >= timeInterval){
                generate_updates_action();
                timeLastProcessed = new Date().getTime();
               } 
               else if (begin){
                generate_updates_action();
                begin = false;
               }
                
            }

        }

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
    <form name="logout_form" action="page1.php" method="POST">
      <div class="sidenav">
        <a href="page1.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="friends.php">Friends</a>
        <a href="requests.php" id='requests'>Friend Requests</a>
        <input type="submit" class="button" name="logout" value="Sign Out">
        <?php create_widget($db, $_SESSION['userid'], date("Y")); ?> 
      </div>
      
      <div class="book" id='updatesdiv' onscroll="scrolled(this)">
        <?php 
          if(isset($_SESSION['username'])) {
            //unset($_SESSION['combination']);
            $user_id = $_SESSION['userid'];
		      	echo "<h2 style='color: maroon'>Welcome to The House Of Words.</h2>";
            echo "<h3>Here are your friends' updates..</h3>";
            echo "<hr style='margin-right: 5%; opacity: 1; border-color: black'>";
            update_friends($db);  //update $_SESSION['friends'] : array of frnd_ids
		      }
		      else{ 
            ?>
            <script type="text/javascript">document.getElementById('updatesdiv').hidden=true;</script>
		      <?php	header("location:login.php");
		      }
         
        echo "<div id='updates_div".$_SESSION['updates_div_counter']."'></div>";
            $ids = get_friends_ids($db); //returns ids split by a comma(string)

            $_SESSION['rowlimit'] = 0;
            $_SESSION['updates_div_counter'] = 0;
            if($ids!=""){
               $_SESSION['i'] = 0; //variable for the stars image elements
               $_SESSION['j'] = 0; //variable for the review(hidden) elements
               $_SESSION['k'] = 0; //variable for like and comment(id for each update)
              // generate_updates_view($rowlimit, $ids, $user_id, $i, $j, $k, $db);
            }
            else {         //if no friends exist
              echo "<h5>It seems to be a little quiet right here..</h5>";
            }
         
          ?>

          <!-- The Modal -->
          <div id="myModal" class="modal">
          <!-- Modal content -->
           <div class="modal-content">
              <span class="close">&times;</span>
              <p id='likers_modal'>Some text in the Modal..</p>
           </div>
        </div>
    <!--    </div>  -->
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
include 'functions_jscript3.php';

  if(isset($ids)) 
    echo " <script type='text/javascript'>generate_updates_action();</script> ";

 ?>
  </body>
</html>

