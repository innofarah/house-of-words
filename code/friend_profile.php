<html>
  <head>
    <title>House Of Words.</title>
    <link rel="stylesheet" type="text/css" href="profile_stylesheet.css">
    <link rel="stylesheet" type="text/css" href="page1_stylesheet.css">
    <script src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="functions_jscript.js"></script>
  </head>

  <body>
    <form name="logout_form" action="friend_profile.php" method="POST" ENCTYPE="multipart/form-data">  
      <div class="sidenav">
        <a href="page1.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="friends.php" id='friends'>Friends</a>
        <a href="requests.php" id='requests'>Friend Requests</a>
        <input type="submit" name="logout" class="button" value="Sign Out"> 
      </div>
    
      <?php
        session_start();  
        //profile  
        if(isset($_GET['userid']) && $_GET['userid'] == $_SESSION['userid']){
          header("location:profile.php");
        }

        include "db_connection.php";
        include "functions.php";
        if(isset($_POST['add'])){
          $add_friend_query = "insert into friend_request values(null,'".$_SESSION['userid']."','".$_SESSION['friend_user_id']."',CURRENT_TIMESTAMP)";
          mysqli_query($db, $add_friend_query);
          header("Refresh:0");
        }

        else if(isset($_POST['cancel'])){
          $cancel_friend_query = "delete from friend_request where from_user='".$_SESSION['userid']."' and to_user='".$_SESSION['friend_user_id']."'";
          mysqli_query($db, $cancel_friend_query);
          header("Refresh:0");
        }

        else if(isset($_POST['confirm'])){
          $accept_friend_query = "insert into friend_relation values(null,'".$_SESSION['userid']."','".$_SESSION['friend_user_id']."')";
          mysqli_query($db, $accept_friend_query);
          $accept_friend_query = "insert into friend_relation values(null,'".$_SESSION['friend_user_id']."','".$_SESSION['userid']."')";
          mysqli_query($db, $accept_friend_query);
            $delete_request_query = "delete from friend_request where from_user='".$_SESSION['friend_user_id']."' and  to_user='".$_SESSION['userid']."'";
          mysqli_query($db, $delete_request_query);
          header("Refresh:0");
        }

        else if(isset($_POST['ignore'])){
          $ignore_friend_query = "delete from friend_request where from_user='".$_SESSION['friend_user_id']."' and  to_user='".$_SESSION['userid']."'";
          mysqli_query($db, $ignore_friend_query);
          header("Refresh:0");
        }

        else if(isset($_POST['remove'])){
          $delete_friend_query = "delete from friend_relation where user_id in ('".$_SESSION['friend_user_id']."','".$_SESSION['userid']."') and friend_user_id in ('".$_SESSION['friend_user_id']."','".$_SESSION['userid']."')";
          mysqli_query($db, $delete_friend_query);
          header("Refresh:0");
        }
        else if(isset($_POST['logout'])) {
         //unset($_SESSION['username']);
          $_SESSION['signed_out'] = true;
          header("location:login.php");
        }
        else if(isset($_POST['follow'])){
          $follow_query = "insert into follow_records values(null,".$_SESSION['userid'].",".$_SESSION['friend_user_id'].")";
          mysqli_query($db, $follow_query);
        }
        else if(isset($_POST['unfollow'])){
          $unfollow_query = "delete from follow_records where follower_id=".$_SESSION['userid']." and followed_id=".$_SESSION['friend_user_id'];
          mysqli_query($db, $unfollow_query);
        }
        update_friends($db);
        if(!isset($_SESSION['username'])) header("location:login.php");
        //use $session for processing requests
        if(isset($_GET['userid']) || isset($_SESSION['friend_user_id'])){
           if(isset($_GET['userid'])){
            $_SESSION['friend_user_id'] = $_GET['userid'];
          }
          
           $user_id = $_SESSION['friend_user_id'];
           $myuser_id = $_SESSION['userid'];
           $user_information_query="select * from user,country where country.code=user.country_code and user_id='".$_SESSION['friend_user_id']."'";
           $user_information_output = mysqli_query($db, $user_information_query);
           if(mysqli_num_rows($user_information_output)==1){
            $line_user = mysqli_fetch_assoc($user_information_output);
            if($line_user['view_privacy']==0 || ($line_user['view_privacy']==1 && is_friend($user_id))){ //if user profile is not private
            $name = $line_user['first_name']." ".$line_user['last_name'];
          echo "<div class='book' style='height: 40%; padding-top: 10px' id='shelf_division'>
          <script type='text/javascript'>
            display_shelf(2,$user_id);
          </script>
        </div>
        <div class='book' style='height: 40%; position: fixed; margin-top: 22%'>
          <h3 style='color: maroon;'>$name Updates..</h3>";
       
           // $ids = get_friends_ids(); //returns ids split by a comma(string)
           // if(isset($_SESSION['userid'])){
           //  $user_id = $_SESSION['userid'];
               $get_my_updates_query = "select *,(NOW()-timestamp) as timediff from updates,user,book where updates.book_id=book.book_id and updates.user_id=user.user_id and updates.user_id=".$user_id." order by updates.timestamp desc";
              // echo $get_friends_updates_query;
               $my_updates_output = mysqli_query($db, $get_my_updates_query);
               $i=0; //variable for the stars image elements
               $j=0; //variable for the review(hidden) elements
               $k=0; //variable for like and comment(id for each update)
               while($line=mysqli_fetch_assoc($my_updates_output)){
                $like_link = "processlikes.php?user_id=".$user_id."&update_id=".$line['update_id'];
                $update_id = $line['update_id'];
          
                echo "<table border='0' width='550px'><tr><td width='55px' valign='top'><img src='user_profile_pics/".$line['image']."' style='object-fit: cover; width:50px; height:50px;'></td>";
                $profile_link = "friend_profile.php?userid=".$line['user_id'];
                $book_link = "book.php?book_id=".$line['book_id'];
                if($line['changed_state']==0){
                  echo "<td valign='center'><a href='".$profile_link."' style='text-decoration: none; color: white; font-weight: bold'>".$line['first_name']." ".$line['last_name']."</a>";
                  if($line['rating']!=null) {
                       echo " has rated a book. <span id='stars".$i."'></span>"; ?>
                        <script type="text/javascript">view_stars(<?php if(isset($line['rating'])) echo $line['rating']; else echo "0"; echo ",$i"; ?>);</script>
                     <?php
                       $i++;
                  }
                  else {
                    echo " has read a book.";
                  }

                  //review -->read more.
                  if($line['review']!=null) {
                    echo "<br>";
                    $review = $line['review'];
                    if(strlen($review)>100){
                     $review_view = substr($review, 0 ,100);
                     $review_hidden = substr($review, 100);
                    }
                    else $review_view = $review;
                   echo "<span>".$review_view;
                   if(isset($review_hidden)) 
                     echo "</span><span id='review".$j."' hidden='true'>".$review_hidden."</span>"."<a href='asd' id='readlink".$j."' onclick='return view(\"readlink".$j."\",\"review".$j."\")' style='color: #FFFF00 '>..read more</a>";
                   $j++;
                   unset($review_hidden);
                  }

                }
                else{
                  echo "<td valign='center'><a href='".$profile_link."' style='text-decoration: none; color: white; font-weight: bold'>".$line['first_name']." ".$line['last_name']."</a>";
                  if($line['changed_state']==1){
                    echo " is reading a book.";
                  }
                  else if($line['changed_state']==2){
                    echo " wants to read a book.";
                  } 
                }
                $timediff = $line['timediff'];
                if($timediff<60)
                  $timediff = floor($timediff)."sec ago";
                else if($timediff/60<60 )
                  $timediff = floor($timediff/60)."min ago";
                else if($timediff/3600<24){
                  $timediffmin = floor(($timediff%3600)/60);
                  $timediff = floor($timediff/3600)."hr, ".$timediffmin."min ago";
                }
                else $timediff = "in ".substr($line['timestamp'], 0,10);

                echo "</td><td align='center' valign='center' width='140px' style='font-family: courier; font-size: 13px'>".$timediff."</td></tr>";
                echo "<tr><td width='55px'></td>";
                echo "<td width='55px'><table border='0'><tr><td rowspan='2' valign='top'><img src='book_pics/".$line['book_image']."' style='object-fit: cover; width:75px; height:auto;'></td>";
                echo "<td valign='top'><a href=$book_link>".$line['book_name']."</a></td></tr>";
                //description-->read more

                $description = $line['description'];
               // if(strlen($description)>100){
                  $description_view = substr($description, 0 ,100);
                  //$description_hidden = substr($description, 100);
                //}
               //else $description_view = $description;
               echo "<tr><td style='padding: 3%; ' valign='top' ><span>".$description_view;
               echo "<a href='".$book_link."'>..read more.</a>";
              // if(isset($description_hidden)) 
              //  echo "</span><span id='desc".$j."' hidden='true'>".$description_hidden."</span>"."<a href='asd' id='readlink".$j."' onclick='return view(\"readlink".$j."\",\"desc".$j."\")' style='color: #FFFF00 '>..read more</a>";*/

                echo "</td></tr></table></td></tr>"; //end of description inner table
                echo "<tr><td></td>";
                $like_link_id = 'like_link'.$k;
                $likes_display_id = 'likes_display'.$k;
                $nb_of_likes_id = 'nb_of_likes'.$k;
                $insertcomment_id = 'insertcomment'.$k;
                $comments_div_id = 'comments_div'.$k;
                $num_likes_query = "select *,coalesce(count(like_id),0) as num_likes from likes where update_id=$update_id group by update_id";
                $likes_output = mysqli_query($db, $num_likes_query);
                $likes_line = mysqli_fetch_assoc($likes_output);
                if(mysqli_num_rows($likes_output)==0) $likes_line="0 likes";
                else $likes_line = $likes_line['num_likes']." likes";
                $user_like_exists_query = "select * from likes where user_id=$myuser_id and update_id=$update_id";
                $user_like_exists_output = mysqli_query($db, $user_like_exists_query);
                if(mysqli_num_rows($user_like_exists_output)==1){
                  $like_text = "Unlike";
                }
                else $like_text = "Like";
                echo "<td><a id='$like_link_id' href='".$like_link."' onclick='return clicked_like($myuser_id,$update_id,\"$like_link_id\",\"$nb_of_likes_id\")'>$like_text</a>&nbsp.&nbsp";
                echo "<a onclick='return display_likes($update_id)' href=''><span id='$nb_of_likes_id' >$likes_line</span></a></td></tr>";
                echo "<tr><td></td><td><textarea rows='2' cols='30' placeholder='Write a comment..' class='insertcomment' id='$insertcomment_id' onkeypress='insertcomment(event, this, $myuser_id, $update_id)'></textarea></td></tr></table>";
                echo "<div class='comments_div' id='$comments_div_id'>";
                display_comments($update_id,$db);
                echo "</div>";
                $k++;
                echo "<hr style='margin-right: 5%; opacity: 0.6'>";
               // echo "</div>";
               }
              
             }else{    //if user profile is private
                echo "<div class='book' style='height: 60%; padding-top: 100px' id='div_privacy'>";
                echo "This user has marked his profile as private. You're not allowed here..";
              }
//}
               ?>

</div>





<?php

            echo "<div class='column'>";
            echo "<div class='card'>";
            echo "<img  src='user_profile_pics/".$line_user['image']."'"."alt='avatar' style='width:100%;'><hr>";
            echo "<input type='submit' onclick='document.logout_form.submit()' ";
                
            $requested_query = "select to_user from friend_request where from_user='".$_SESSION['userid']."' and to_user='".$_SESSION['friend_user_id']."'";
            $output = mysqli_query($db, $requested_query);
            $accept_query = "select to_user from friend_request where to_user='".$_SESSION['userid']."' and from_user='".$_SESSION['friend_user_id']."'";
            $output1 = mysqli_query($db, $accept_query);
                if(mysqli_num_rows($output) == 1) echo "name='cancel' value='Cancel'>";
                else if(mysqli_num_rows($output1) == 1) {
                  echo "name='confirm' value='Confirm'</br>  ";
                  echo "<input type='submit' name='ignore' value='Ignore'> ";
                }
                else if(isset($_SESSION['friends']) && in_array($_SESSION['friend_user_id'], $_SESSION['friends'])) echo "name='remove' value='Remove Friend'>";
                else {
                  echo "name='add' value='Add as a Friend'>";
                }

                if($line_user['follow_privacy']==0 && isset($_SESSION['friends']) && !in_array($_SESSION['friend_user_id'], $_SESSION['friends'])){ //allowed to be followed
                    $followed_query = "select * from follow_records f where f.followed_id=".$_SESSION['friend_user_id']." and f.follower_id=".$_SESSION['userid'];
                 //   echo $followed_query;
                    $followed_output = mysqli_query($db, $followed_query);
                    if(mysqli_num_rows($followed_output)==1)
                      echo "&nbsp<input class='button' type='submit' name='unfollow' value='Unfollow'>";
                    else
                      echo "&nbsp<input class='button' type='submit' name='follow' value='Follow'>";
                  }
            echo "<div class='container'>";
            echo "<h2>".$line_user['first_name']." ".$line_user['last_name']."</h2>";
            echo "<h3 style='color:rgba(249,194,95,.5)'>About</h3>";
            echo "<h4>".$line_user['about_me']."</h4>";
            if($line_user['view_privacy']==0 || ($line_user['view_privacy']==1 && is_friend($user_id))){
               echo "<a href='read' style='color:rgba(249,194,95,.8)' onclick='return display_shelf(0,$user_id)'>Read</a></br>";
            echo "<a href='wanttoread' style='color:rgba(249,194,95,.8)' onclick='return display_shelf(2,$user_id)'>Want to read</a></br>";
            echo "<a href='currently' style='color:rgba(249,194,95,.8)' onclick='return display_shelf(1,$user_id)'>Currently reading</a></br>";   
            }
          echo "<h4>Details</h4>";
          echo "<p>";
          if($line_user['gender']=='M') echo "Male, ";
          else echo "Female, ";
          echo $line_user['Name'];
          echo "</p>";
          echo "Born on ".$line_user['DOB'];
            echo "</div></div></div>";
          }
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
    </form>
  </body>
</html>
