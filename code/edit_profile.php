<html>
  <head>
    <title>House Of Words. Profile</title>
    <?php 
      session_start();
      include 'db_connection.php';
      include 'functions.php';
      if(!isset($_SESSION['username'])) header("location:login.php");
      if(isset($_SESSION['userid'])){
             $user_id = $_SESSION['userid'];
           } ?>
    <link rel="stylesheet" type="text/css" href="page1_stylesheet.css">
    <link rel="stylesheet" type="text/css" href="profile_stylesheet.css">
    <script src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="functions_jscript.js"></script>
 <style type="text/css">  a {
      color: maroon;
      text-decoration: none;
    }
    
    a:visited{
      color: maroon;
      text-decoration: none;
    }

    .modal a{
      color: black;
    }

    .sidenav a {
      color: white;
      text-decoration: none;
    }
    
    .sidenav a:visited{
      color: white;
      text-decoration: none;
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
    

      <?php

       $user_information_query="select * from user where user_name='".$_SESSION['username']."'";
       $user_information_output = mysqli_query($db, $user_information_query);
       if(mysqli_num_rows($user_information_output)==1){
          $line = mysqli_fetch_assoc($user_information_output);
          $fname = $line['first_name'];
          $lname = $line['last_name'];
          $username = $line['user_name'];
          $gender = $line['gender'];
          $country_code = $line['country_code'];
          $DOB = $line['DOB'];
          @$date_array = split("-", $DOB);
          $about_me = $line['about_me'];
          $view_privacy = $line['view_privacy'];
          $follow_privacy = $line['follow_privacy'];
        }

        $interests_query = "select * from interests, user_interest where interests.interest_id = user_interest.interest_id and user_interest.user_id = $user_id";
        $interests_output = mysqli_query($db, $interests_query);
        $interests_text = "";
        while($line=mysqli_fetch_assoc($interests_output)){
          $interests_text .= $line['interest_name'].", " ;
        }
        $interests_text = substr(trim($interests_text), 0, -1);
      ?>


        </form>
        <form name="edit_form" action="process_edit_profile.php" method="post" ENCTYPE="multipart/form-data">
        <div class="book" style="height: 80%; position: fixed; margin-top: 1%; padding-bottom: 1%">
          <h3 style="color: maroon;">Edit Profile</h3>
          
          <h5>First Name</h5>
          <input type="text" name="fname" value="<?php echo $fname ?>">
          </br>
          <h5>Last Name</h5>
          <input type="text" name="lname" value="<?php echo $lname ?>"></br>
          <h5>UserName</h5>
          <input type="text" name="username" value="<?php echo $username ?>">
          </br>
          <h5>Gender</h5>
          <select name="gender">
            <option value="M" <?php if($gender == 'M') echo "selected";  ?> >Male</option>
            <option value="F" <?php if($gender == 'F') echo "selected";  ?>>Female</option>
          </select></br>
          <h5>Country</h5>
          <select name='country'>
                <option value="0" >Choose Your Country</option>
                <?php
                  $query = "select code,name from country";
                  $output = mysqli_query($db, $query);
                  while($line=mysqli_fetch_assoc($output)){
                    echo "<option value='".$line['code']."'";
                    if($country_code == $line['code'])
                     echo " selected ";
                    echo ">".$line['name']."</option>";
                  }
                ?>
              </select>
            <h5>Date of Birth</h5>
            <select name="day">
              <option value=""> </option>
              <?php
              for($i=1; $i<=31; $i++){
                  echo "<option value='".$i."' ";
                  if((int)$date_array[2] == $i)
                    echo " selected ";
                  echo ">".$i."</option>";
                }
                ?>
            </select>&nbsp&nbsp
            <select name="month">
                        <option value="">
                        <!-- -->
                        </option>
                        <option value="01" <?php if($date_array[1]=='01') echo "selected"; ?>>Jan</option>
                        <option value="02" <?php if($date_array[1]=='02') echo "selected"; ?>>Feb</option>
                        <option value="03" <?php if($date_array[1]=='03') echo "selected"; ?>>Mar</option>
                        <option value="04" <?php if($date_array[1]=='04') echo "selected"; ?>>Apr</option>
                        <option value="05" <?php if($date_array[1]=='05') echo "selected"; ?>>May</option>
                        <option value="06"  <?php if($date_array[1]=='06') echo "selected"; ?>>Jun</option>
                        <option value="07" <?php if($date_array[1]=='07') echo "selected"; ?>>Jul</option>
                        <option value="08" <?php if($date_array[1]=='08') echo "selected"; ?>>Aug</option>
                        <option value="09" <?php if($date_array[1]=='09') echo "selected"; ?>>Sep</option>
                        <option value="10" <?php if($date_array[1]=='10') echo "selected"; ?>>Oct</option>
                        <option value="11" <?php if($date_array[1]=='11') echo "selected"; ?>>Nov</option>
                        <option value="12" <?php if($date_array[1]=='12') echo "selected"; ?>>Dec</option>
            </select>&nbsp&nbsp
            <select name="year">
              <option value=" "> </option>
              <?php
                $current_year = date("Y");
                for($i=1900; $i<=$current_year; $i++){
                  echo "<option value='".$i."' ";
                  if((int)$date_array[0] == $i)
                    echo " selected";
                  echo ">".$i."</option>";
                }
              ?>
            </select>

            <h5>Interests</h5>
            <input type="text" name="interests" value="<?php echo $interests_text; ?>"><i>(seperated by comma)</i>

            <h5>About Me</h5>
            <textarea name="about_me"><?php echo $about_me ?></textarea>

            <h5>Upload Image</h5>
            <input type='file'  name='pic' accept='image/*'></br>
            <h3 style="color: maroon;">Edit Privacy</h3>
            <h5>Who can view my profile:</h5>
            <input type="radio" name="viewPriv" value="0" <?php if($view_privacy==0) echo "checked" ?>> Anyone</br>
            <input type="radio" name="viewPriv" value="1"  <?php if($view_privacy==1) {echo "checked"; } ?>> Just Friends</br>

            <h5>Allow non-friends to follow my reviews:</h5>
            <input type="checkbox" name="followPriv" id="followPriv" <?php if($follow_privacy == 0) echo "checked "; ?>> Allow </br></br>
            <input type="submit" name="update" value="Update Changes" class="button" style="width:30%" >


      </div>
    </form>





      <?php
        if(isset($_POST['logout'])) {
         //unset($_SESSION['username']);
          $_SESSION['signed_out'] = true;
          header("location:login.php");
        }
        
        $user_information_query="select * from user,country where country.code=user.country_code and user_name='".$_SESSION['username']."'";
        $user_information_output = mysqli_query($db, $user_information_query);
        if(mysqli_num_rows($user_information_output)==1){
          $line = mysqli_fetch_assoc($user_information_output);
          echo "<div class='column'>";
          echo "<div class='card'>";
          echo "<a href='edit_profile.php' style='color:rgba(249,194,95,.8)' >Edit Profile</a></br>";
          echo "<img  src='user_profile_pics/".$line['image']."'"."alt='avatar' style='width:100%; border-radius:2%'>";
          echo "<div class='container'>";
          echo "<h2>".$line['first_name']." ".$line['last_name']."</h2>";
          echo "<h3 style='color:rgba(249,194,95,.5)'>About me</h3>";
          echo "<h4>".$line['about_me']."</h4>";
          echo "<h4>Details</h4>";
          echo "<p>";
          if($line['gender']=='M') echo "Male, ";
          else echo "Female, ";
          echo $line['Name'];
          echo "</p>";
          echo "Born on ".$line['DOB'];
          echo "</div></div></div>";
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
    
<script type="text/javascript">
  //function processradio(elt){
  //if(elt.value == "anyone")
   //document.getElementById("reviewPriv").disabled = false;
  //else
   // document.getElementById("reviewPriv").disabled = true;

//}

  function submitupdates(){
    document.edit_form.submit();
  }


</script>

  </body>
</html>
  