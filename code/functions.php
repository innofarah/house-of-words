<?php
//session_start();
//$_SESSION['rowlimit'] = 0;   //check
//  generate_updates_view();
include 'db_connection.php';


function is_friend($user1_id){
  $ids = $_SESSION['friends'];
  if (in_array($user1_id, $ids))
    return true;
  return false;
}

 function update_friends($db){
 			$friends = array();
      $friends_query = "select friend_user_id from friend_relation where user_id='".$_SESSION['userid']."'";
      $output = mysqli_query($db, $friends_query);
      while ($line=mysqli_fetch_assoc($output)) {
        $friends[] = $line['friend_user_id'];
      }
      if(isset($friends)) $_SESSION['friends'] = $friends;
  }

 function get_friends_ids($db){
 	$ids="";
 	if(isset($_SESSION['friends'])){
 		$friends = $_SESSION['friends'];       
 		if(sizeof($friends)>0){     
   			for($i=0;$i<sizeof($friends)-1;$i++) {
    			$ids .= "$friends[$i]x";
    		}
    		$ids .= "$friends[$i]";
 		}
 	}
    return $ids;
 }


 function display_comments($update_id,$db){
  $select_comments_query = "select * from comments,user where user.user_id=comments.user_id and comments.update_id=$update_id";
  $result = mysqli_query($db, $select_comments_query);
  echo "<table border='0' style='word-wrap: break-word; table-layout:fixed' width='500px'>";
  while($line = mysqli_fetch_assoc($result)){
    $image = $line['image'];
    $first_name = $line['first_name'];
    $last_name = $line['last_name'];
    $user_id = $line['user_id'];
    $comment_txt = str_replace('\\', '', $line['comment_text']);

    //echo "<span id='numlikes'>";
    echo "<tr><td width='50px' height='auto' valign='top'><img src='user_profile_pics/$image' width='50px' height='50px'></td>";
    echo "<td style='font-weight:bold; color:white;' width='400px'>";
    $href = "friend_profile.php?userid=$user_id";
    echo "<a href='".$href."'>$first_name&nbsp&nbsp$last_name</a>";
    echo "<br><span style='color:black; font-weight:italic; width:500px;'>$comment_txt</span></td></tr>";
  }
  echo "</table>";
 }


 function generate_updates_view($db){
    if(isset($_SESSION['i'])){
    $rowlimit = $_SESSION['rowlimit'];
    $ids = get_friends_ids($db);
     //add the followed ids to $ids:
            @$ids_array = split("x", $ids);
            $followed_query = "select * from follow_records f where f.follower_id=".$_SESSION['userid'];
            $followed_output = mysqli_query($db, $followed_query);
            while($line = mysqli_fetch_assoc($followed_output)){
              if(!in_array($line['followed_id'], $ids_array)){
                $ids .= "x".$line['followed_id'];
              }
            }
    $user_id = $_SESSION['userid'];
    $i = $_SESSION['i'];
    $j = $_SESSION['j'];
    $k = $_SESSION['k'];
    $updates_div_counter = $_SESSION['updates_div_counter'];
    $ids = str_replace("x", ",", $ids);
     $get_friends_updates_query = "select *,(NOW()-timestamp) as timediff from updates,user,book where updates.book_id=book.book_id and updates.user_id=user.user_id and updates.user_id in (".$ids.") order by updates.timestamp desc limit $rowlimit,10";
             //  echo $get_friends_updates_query;
               $friends_updates_output = mysqli_query($db, $get_friends_updates_query);
               
               while($line=mysqli_fetch_assoc($friends_updates_output)){
                $like_link = "processlikes.php?user_id=".$user_id."&update_id=".$line['update_id'];
                $update_id = $line['update_id'];
          
                echo "<table border='0' width='550px'><tr><td width='55px' valign='top'><img src='user_profile_pics/".$line['image']."' style='object-fit: cover; width:50px; height:50px;'></td>";
                $profile_link = "friend_profile.php?userid=".$line['user_id'];
                $book_link = "book.php?book_id=".$line['book_id'];
                if($line['changed_state']==0){  //read state
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
                   unset($review_hidden);  //for next hidden reviewtext
                  }

                }
                else{ //if state not read
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
                $likes_display_id = 'likes_display'.$k; //??
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
                echo "<td><a id='$like_link_id' href='".$like_link."' onclick='return clicked_like($user_id,$update_id,\"$like_link_id\",\"$nb_of_likes_id\")'>$like_text</a>&nbsp.&nbsp";
                echo "<a onclick='return display_likes($update_id)' href=''><span id='$nb_of_likes_id' >$likes_line</span></a></td></tr>";
                echo "<tr><td></td><td><textarea rows='2' cols='30' placeholder='Write a comment..' class='insertcomment' id='$insertcomment_id' onkeypress='insertcomment(event, this, $user_id, $update_id)'></textarea></td></tr></table>";
                echo "<div class='comments_div' id='$comments_div_id'>";
                display_comments($update_id,$db);
                echo "</div>";
                $k++;
                echo "<hr style='margin-right: 5%; opacity: 0.6'>";
               }

               //assign the session variables to the new values for the next generation of updates.
               $_SESSION['i'] = $i;
               $_SESSION['j'] = $j;
               $_SESSION['k'] = $k;
               $_SESSION['rowlimit'] = $rowlimit + 10;
               $_SESSION['updates_div_counter'] = $updates_div_counter + 1;
             }
            //   print_r($_SESSION);
               //echo "</div><div id='updates_div_counter".$_SESSION['updates_div_counter']."'>ddsdsdsdsdsds";
 }
 
?>