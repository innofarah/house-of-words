<html>
  <head>
    <title>House Of Words. Register</title>
    <style>
      body{
        font-family: "Raleway", sans-serif;
        background-image: url('BdEayK-01.jpeg');
        min-height: 100%;
        position:relative;
        scrolling : none;
        background-position: center;
        background-size: cover;
      }

      
      .column > div{
        top: 0.1%;
        float: left;
        width: 33.3%;
        margin-bottom: 16px;
        padding: 0 8px;
        opacity:0.8;
        background-color:#f9c25f; 
        width:15%;
        margin-left:70%;
        border-radius:5%;
        padding:8% 5% ;
        height: 70%;
        position: fixed;
        overflow-x: hidden;
        z-index: 1;
      }

      @media screen and (max-width: 650px) {
        .column {
        width: 100%;
        display: block;
        }
      }

      .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      }

      .container {
        padding: 0 16px;
      }

      .container::after, .row::after {
        content: "";
        clear: both;
        display: table;
      }
      .button {
        border: none;
        outline: 0;
        display: inline-block;
        padding: 8px;
        color: white;
        background-color: #f9c25f;
        text-align: left;
        cursor: pointer;
        width: 100%;
      }

      .button:hover {
        background-color :#f9c25f;
        color:black;
      } 

/* Style the side navigation */
      .sidenav {
        height: 100%;
        width: 200px;
        position: fixed;
        z-index: 1;
        top: 0.1%;
        left: 0;
        background-color: #f9c25f;
        overflow-x: hidden;
        opacity : 0.8 ; 
        border-radius: 7%;
      }


/* Side navigation links */
      .sidenav a {
        color: white;
        padding: 16px;
        text-decoration: none;
        display: block;
      }

/* Change color on hover */
      .sidenav a:hover {
        background-color: #f9c25f;
        color: black;
      }

      .friends{
        margin-left: 20%;
        margin-right: 40%;
        margin-top: 0%;
        padding-top: 5%;
        padding-left: 10%;
        background-color: #f9c25f;
        overflow-x: hidden;
        opacity : 0.3; 
        border-radius: 1%;
      }


    </style>

  </head>

  <body>
    <form name="logout_form" action="profile.php" method="POST" ENCTYPE="multipart/form-data">  
      <div class="sidenav">
        <a href="page1.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="friends.php" id='friends'>Friends</a>
        <a href="requests.php" id='requests'>Friend Requests</a>
        <input type="submit" name="logout" class="button" value="Sign Out"> 
      </div>
    
      <div class="friends" id="friendsdiv">
        <?php
          include 'db_connection.php';
          session_start();
          $show_friends_query = "select friend_user_id from friend_relation where user_id='".$_SESSION['userid']."'";
        //  if(!isset($_SESSION['friends'])){
            $output = mysqli_query($db, $show_friends_query);
            while($line = mysqli_fetch_assoc($output)){
              echo "<a href='./friend_profile.php?userid=".$line['friend_user_id']."'>".$line['friend_user_id']."</a>";
              echo "<br>";
            }
          
        ?>
      </div>

      <?php
       // session_start();
        if(isset($_POST['logout'])) {
         //unset($_SESSION['username']);
          $_SESSION['signed_out'] = true;
          header("location:login.php");
        }
        if(!isset($_SESSION['username'])) header("location:login.php");
        $user_information_query="select * from user where user_name='".$_SESSION['username']."'";
        $user_information_output = mysqli_query($db, $user_information_query);
        if(mysqli_num_rows($user_information_output)==1){
          $line = mysqli_fetch_assoc($user_information_output);
          echo "<div class='column'>";
          echo "<div class='card'>";
          echo "<img  src='user_profile_pics/".$line['image']."'"."alt='avatar' style='width:100%;'>";
          echo "<div class='container'>";
          echo "<h2>".$_SESSION['username']."</h2>";
          echo "<p class='title'>".$line['first_name']."</p>";
          echo "<p>".$line['gender']."</p>";
          echo "<p>".$line['email']."</p>";    
          echo "</div></div></div>";
        }
        
      ?>
    </form>
  </body>
</html>
  