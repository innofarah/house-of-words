<html>
  <head>
    <title>House Of Words. Book</title>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
    document.addEventListener("keypress",function(event){
      if(event.keyCode==13)
          event.preventDefault();
      });
    </script>
    <script type="text/javascript" src="functions_jscript.js"></script>
    <link rel="stylesheet" type="text/css" href="book_stylesheet.css">
    <?php session_start();
          include 'db_connection.php';  
          include 'functions.php';       
          if(isset($_GET['book_id'])){
            $book_id = $_GET['book_id'];
            $book_query = "select * from book where book_id=".$book_id;
            $output = mysqli_query($db, $book_query);
            if($line = mysqli_fetch_assoc($output)) {}
            else header("location:page1.php");
          }
          else header("location:page1.php");

          if(isset($_SESSION['userid'])){
            update_friends($db);
            $ids = get_friends_ids($db);
            $ids = str_replace("x", ",", $ids);   //see
            $user_id = $_SESSION['userid'];
            //$_SESSION['userid'] = $user_id;  ??
            $book_state_query = "select * from added_book where book_id=".$book_id." and user_id=".$user_id;
            $book_state_output = mysqli_query($db, $book_state_query);
            $line2 = mysqli_fetch_assoc($book_state_output);
          }
          //updating the   book's state
           if(isset($_GET['state'])) {   
            $state = $_GET['state'];
            if(isset($line2['state'])){
              $_SESSION['row_exists'] = true;
            }
            else 
              $_SESSION['row_exists'] = false;
            if($state != 0) {  //want to read or readingnow(update-or-ins)
              if(!$_SESSION['row_exists']){
                $add_bookstate_query = "insert into added_book values(null,".$user_id.",".$book_id.",".$state.",null,null,CURRENT_TIMESTAMP)";
                mysqli_query($db, $add_bookstate_query);
              }
              else {
                $update_bookstate_query = "update added_book set state=".$state.",rating=null,review=null where user_id=".$user_id." and book_id=".$book_id;
                $output = mysqli_query($db, $update_bookstate_query);
                echo $update_bookstate_query;
              }
              $location = "book.php?book_id=".$book_id;
              header("location:$location");
            }
            else {
              header("location:rate.php?book_id=$book_id");
            } 
          }
    //the action of want to read button
    $action = "book.php?book_id=".$book_id."&state=2"; 
    echo "</head><body>
    <form name='bookform' action=".$action." method='POST' ENCTYPE='multipart/form-data'>  
      <div class='sidenav'>
        <a href='page1.php'>Home</a>";
        if(isset($_SESSION['username'])) {
          echo "<a href='profile.php'>Profile</a>
                <a href='friends.php'>Friends</a>
                <a href='requests.php'>Friend Requests</a>
                <input type='submit' name='logout' class='button' value='Sign Out' onclick='document.bookform.action=\"book.php?book_id=$book_id\"'>";   
          }
        else {
          echo  "<a href='register.php' >Join Us</a>";
        }
         
     echo "</div>
    
      <div class='book' id='bookdiv'>"; 
     
        echo "<table border='0'>";
        echo  "<tr><td rowspan='2' valign='top'><img class='book_img' style='margin-right: 20px;' src='book_pics/".$line['book_image']."'"." alt='avatar'></br>";
        if(isset($_SESSION['username'])){
          echo "<button class='dropbtn' name='statebtn' ";
          if(mysqli_num_rows($book_state_output)==0){
            echo " onclick='document.bookform.submit()'>";
            echo "Want to Read</button>";
          }
          else{
            echo "onmouseover='this.style.backgroundColor= \"rgba(249,194,95,.1)\"; this.style.color=\"white\"' onclick='return false'>";
            if($line2['state']==0)
              echo "Read";
            else if($line2['state']==1)
              echo "Reading Now";
            else if($line2['state']==2)
              echo "Want to Read";
          }
          echo "</button>
          <div class='dropdown'>
                <input type='image' src='dropdown_down.png' class='btnimg' onmouseover='"."this.src=\"dropdown_up.png\"' onmouseout='"."this.src=\"dropdown_down.png\"' onclick='return false' "."></button>
                <div class='dropdown-content'>";
                $href = "book.php?book_id=$book_id&state=";
                if(!isset($line2['state']) || $line2['state']!=1) echo "<a href='".$href."1' value='1'>Reading Now</a>";
                if(!isset($line2['state']) || $line2['state']!=0) echo "<a href='".$href."0' value='0'>Read</a>";
                if(isset($line2['state']) && $line2['state']!=2) echo "<a href='".$href."2' value='2'>Want to Read</a>";
                //checkbox for adding a new shelf
                echo "<input type='checkbox' name='asd'>
                </div>
              </div>";
             if(isset($line2['state']) && $line2['state']==0){
              echo "<span style='margin-left: 10%; margin-top: 10%; font-size: 16px; opacity: 0.6;'>My Rating</span></br>";
              echo "<span id='stars1' style='margin-left: 17%;'></span>";
           }
        } ?>
        <script type="text/javascript">view_stars(<?php if(isset($line2['rating'])) echo $line2['rating']; else echo "0"; echo ",1" ?>);</script>

      <?php
        echo "</td>";
        echo "<td valign='top' align='left' style='padding-left: 3%' colspan='2'><p style='color: maroon; font-size: 30px; font-weight: bold; margin-bottom:1%'>".$line['book_name']."</p>";
       
         $authors_query="select * from author,book_author,role where book_author.book_id=".$book_id." and author.author_id=book_author.author_id and book_author.role_id=role.role_id";
         $authors_output = mysqli_query($db, $authors_query);
         echo "<span style='color: rgba(255,255,255,0.7);'>by ";
          while($line1=mysqli_fetch_assoc($authors_output)){
            echo $line1['fname']." ".$line1['lname']."(".$line1['role_name'].") ";
          }
          echo "</span>";
          $rating = $line['avg_rating'];
        echo "</br>";
        echo "<progress value='".$rating."'' max='5' style='height: 5px; width: 85px'></progress>&nbsp&nbsp&nbsp<span style='color:rgba(116, 164, 242,0.8);'>".$rating."&nbsp&nbsp".$line['num_ratings']." ratings</span>";
        echo "</td></tr>";

        $description = $line['description'];
        if(strlen($description)>300){
          $description_view = substr($description, 0 ,300);
          $description_hidden = substr($description, 300);
          }
          else $description_view = $description;
          echo "<tr><td style='padding: 3%; ' valign='top' ><span>".$description_view;
          if(isset($description_hidden)) 
           echo "</span><span id='desc' hidden='true'>".$description_hidden."</span>"."<a href='asd' id='readlink' onclick='return view(\"readlink\",\"desc\")' style='color: #FFFF00 '>..read more</a>";
         echo  "</td></tr></table>";
         echo "<hr style='margin-right: 5%; opacity: 0.6'>";
          $i=2;
          $j=0; //variable for the review(hidden) elements
          $k=0; //variable for like and comment(id for each update)

         if(isset($_SESSION['userid'])){      //no friends reviews if not logged in
           echo "<h3 style='color: maroon'>My Review</h3><hr style='opacity: 0.3; margin-right: 5%'>";
           if(isset($line2['state']) && $line2['state']==0 && $line2['review']!=null)
            echo "<span>".$line2['review']."</span>";
           else {
            if(isset($line2['state']) && $line2['state']==0)
              echo "You've marked it as 'Read'";
            else if(isset($line2['state']) && $line2['state']==1)
              echo "You've marked it as 'Reading now'";
            else if(isset($line2['state']) && $line2['state']==2)
              echo "You've marked it as 'Want to read'"; 
            echo "</br><span>You have not written any review yet.</span><pre style='font-family: courier; font-size: 13px'>".$line2['timestamp']."</pre>";
           }
           echo "<h3 style='color: maroon'>Friends Reviews</h3><hr style='opacity: 0.3; margin-right: 5%'>";
           if($ids=="") echo "You have not added any friends yet.";
           else {
            $get_friends_reviews_query = "select * from user a,updates b where a.user_id=b.user_id and b.user_id in (".$ids.") and b.book_id=".$book_id." and b.timestamp in (select max(timestamp) from updates where user_id=b.user_id and book_id=b.book_id group by user_id,book_id)";
            
            $friends_reviews_output = mysqli_query($db, $get_friends_reviews_query);
            if(mysqli_num_rows($friends_reviews_output)==0)
              echo "None of your friends have added any reviews of ".$line['book_name']." yet.";
              else {
                while($line3=mysqli_fetch_assoc($friends_reviews_output)){
                  echo "<table border='0' width='550px'><tr><td  rowspan='4' valign='top' width='55px'><img src='user_profile_pics/".$line3['image']."' style='object-fit: cover; width:50px; height:50px;'></td>";
                  $profile_link = "friend_profile.php?userid=".$line3['user_id'];
                  if($line3['changed_state']==0){
                      echo "<td><a href='".$profile_link."' style='text-decoration: none; color: white; font-weight: bold'>".$line3['first_name']." ".$line3['last_name']."</a>";
                      if($line3['rating']!=null) {
                       echo " has rated it <span id='stars".$i."'></span>"; ?>
                        <script type="text/javascript">view_stars(<?php if(isset($line3['rating'])) echo $line3['rating']; else echo "0"; echo ",".$i ?>);</script>
                      <?php
                       $i++;
                      }
                     else {echo " has read it.";}
                     echo "</td><td align='right' width='180px' style='font-family: courier; font-size: 13px'>".$line3['timestamp']."</td></tr>";
                     if($line3['review']!=null) echo "<tr><td>".$line3['review']."</td></tr>";
                  }
                  else{
                    echo "<tr><td><a href='".$profile_link."' style='text-decoration: none; color: white; font-weight: bold'>".$line3['first_name']." ".$line3['last_name']."</a> ";
                    if($line3['changed_state']==1) 
                      echo "is reading it now.</td>";
                    else if($line3['changed_state']==2) 
                      echo "marked it as 'Want to read'.</td>";
                    echo "</td><td align='right' width='180px' style='font-family: courier; font-size: 13px'>".$line3['timestamp']."</td></tr>";
                  } 
                  $like_link = "processlikes.php?user_id=".$user_id."&update_id=".$line3['update_id'];
                  $update_id = $line3['update_id']; 
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
                $user_like_exists_query = "select * from likes where user_id=$user_id and update_id=$update_id";
                $user_like_exists_output = mysqli_query($db, $user_like_exists_query);
                if(mysqli_num_rows($user_like_exists_output)==1){
                  $like_text = "Unlike";
                }
                else $like_text = "Like";
                echo "<tr><td><a id='$like_link_id' href='".$like_link."' onclick='return clicked_like($user_id,$update_id,\"$like_link_id\",\"$nb_of_likes_id\")'>$like_text</a>&nbsp.&nbsp";
                echo "<a onclick='return display_likes($update_id)' href=''><span id='$nb_of_likes_id' >$likes_line</span></a></td></tr>";
                echo "<tr><td><textarea rows='2' cols='30' placeholder='Write a comment..' class='insertcomment' id='$insertcomment_id' onkeypress='insertcomment(event, this, $user_id, $update_id)'></textarea></td></tr>";
                echo "<tr>";
                if($line3['review']!=null) echo "<td></td>";
                echo "</tr></table>";
                echo"<div style='margin-left:57px;'><button type='button' onclick='"."hide_view(\"$comments_div_id\")"."'>View comments</button></br><div class='comments_div' id='$comments_div_id' hidden='true'>";
                display_comments($update_id,$db);
                echo "</div></div>";
                $k++;

                }//for while all lines
              
              }
           }
           echo "<h3 style='color: maroon'>Community Reviews</h3><hr style='opacity: 0.3; margin-right: 5%'>";
           $get_community_reviews_query = "select * from user a,updates b where a.user_id=b.user_id and b.user_id not in (".$ids.") and b.book_id=".$book_id." and b.timestamp in (select max(timestamp) from updates where user_id=b.user_id and book_id=b.book_id group by user_id,book_id)";
         }
        else {              //if not signed in
           $get_community_reviews_query = "select * from user a,updates b where a.user_id=b.user_id and b.book_id=".$book_id." and b.timestamp in (select max(timestamp) from updates where user_id=b.user_id and book_id=b.book_id group by user_id,book_id)";
        }
        //in all cases , if signed in or not, friends exist or not

        $community_reviews_output = mysqli_query($db, $get_community_reviews_query);
        if(mysqli_num_rows($community_reviews_output)==0){
          echo "It seems to be a little quiet right here..";
        } 
        // else if there is community reviews
        else {
             while($line4=mysqli_fetch_assoc($community_reviews_output)){
                  echo "<table border='0' width='550px'><tr><td  rowspan='4' valign='top' width='55px'><img src='user_profile_pics/".$line4['image']."' style='object-fit: cover; width:50px; height:50px;'></td>";
                  $profile_link = "friend_profile.php?userid=".$line4['user_id'];
                  if($line4['changed_state']==0){
                      echo "<td><a href='".$profile_link."' style='text-decoration: none; color: white; font-weight: bold'>".$line4['first_name']." ".$line4['last_name']."</a>";
                      if($line4['rating']!=null) {
                       echo " has rated it <span id='stars".$i."'></span>"; ?>
                        <script type="text/javascript">view_stars(<?php if(isset($line4['rating'])) echo $line4['rating']; else echo "0"; echo ",$i"; ?>);</script>
                     <?php
                       $i++;
                      }
                     else {echo " has read it.";}
                     echo "</td><td align='right' width='180px' style='font-family: courier; font-size: 13px'>".$line4['timestamp']."</td></tr>";
                     if($line4['review']!=null) echo "<tr><td>".$line4['review']."</td></tr>";
                  }
                  else{
                    echo "<td><a href='".$profile_link."' style='text-decoration: none; color: white; font-weight: bold'>".$line4['first_name']." ".$line4['last_name']."</a> ";
                    if($line4['changed_state']==1) 
                      echo "is reading it now.</td>";
                    else if($line4['changed_state']==2) 
                      echo "marked it as 'Want to read'.</td>";
                    echo "</td><td align='right' width='180px' style='font-family: courier; font-size: 13px'>".$line4['timestamp']."</td></tr>";
                  } 
                  

                  $update_id = $line4['update_id']; 
                  $nb_of_likes_id = 'nb_of_likes'.$k;
                  $num_likes_query = "select *,coalesce(count(like_id),0) as num_likes from likes where update_id=$update_id group by update_id";
          
                  $likes_output = mysqli_query($db, $num_likes_query);
                  $likes_line = mysqli_fetch_assoc($likes_output);
                  if(mysqli_num_rows($likes_output)==0) $likes_line="0 likes";
                  else $likes_line = $likes_line['num_likes']." likes";
                  

                  if(isset($_SESSION['userid'])) {
                    $like_link = "processlikes.php?user_id=".$user_id."&update_id=".$line4['update_id']; 
                    $like_link_id = 'like_link'.$k;
                    $likes_display_id = 'likes_display'.$k;
                    $user_like_exists_query = "select * from likes where user_id=$user_id and update_id=$update_id";

                    $user_like_exists_output = mysqli_query($db, $user_like_exists_query);
                    if(mysqli_num_rows($user_like_exists_output)==1){
                      $like_text = "Unlike";
                    }
                    else $like_text = "Like";
                    echo "<tr><td><a id='$like_link_id' href='".$like_link."' onclick='return clicked_like($user_id,$update_id,\"$like_link_id\",\"$nb_of_likes_id\")'>$like_text</a>&nbsp.&nbsp";
                
                  }
                  else echo "<tr><td>";
                  echo "<a onclick='return display_likes($update_id)' href=''><span id='$nb_of_likes_id' >$likes_line</span></a></td></tr></table>";
                 $comments_div_id = 'comments_div'.$k;
                 echo "<div style='margin-left:57px;'><button type='button' onclick='"."hide_view(\"$comments_div_id\")"."'>View comments</button></br><div class='comments_div' id='$comments_div_id' hidden='true'>";
                  display_comments($update_id,$db);
                 echo "</div></div></br>";
                  $k++;  
            } //for while
                                    
        }
        echo "</div>";
        echo "</div>";
     
       // if(!isset($_SESSION['username'])) header("location:login.php");
        $author_information_query="select * from author,book_author,country where book_author.book_id=".$book_id." and book_author.role_id=1 and author.author_id=book_author.author_id and author.country_code=country.code";
        $author_information_output = mysqli_query($db, $author_information_query);
        if(mysqli_num_rows($author_information_output)==1){
          $line = mysqli_fetch_assoc($author_information_output);
          echo "<div class='column'>";
          echo "<div class='card'>";
          echo "<img  src='author_pics/".$line['image']."'"." alt='author_pics/avatar.jpg' style='width:100%;'>";
          echo "<div class='container'>";
          echo "<h2 style='color:white;'>".$line['fname']." ".$line['lname']."</h2>";
          //print_r($line);
          echo "<h3 style='color: rgba(255,255,255,0.6);'>".$line['Name']."</h3>";
          $author_id = $line['author_id'];
          $href = "author.php?author_id=$author_id";
          echo "<a href='".$href."' id='authorlink' style='color: rgba(255,255,255,0.8); text-decoration: none; background-color: maroon; :hover:yellow'>Know the Author</a>";
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
    </form>
  </body>
</html>
  