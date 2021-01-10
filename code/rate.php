<html>
  <head>
    <title>House Of Words. Register</title>
    <link rel="stylesheet" type="text/css" href="rate_stylesheet.css">
    <script>
    	function view_stars(myRating){
    		var theImage = "";
    		for(var i=0; i<myRating.value; i++)
    			theImage += "<img src='star.png' width='20px' height='20px'>";
    		document.getElementById('stars').innerHTML = theImage;
    	}
    </script>
    <?php session_start();
          include 'db_connection.php';
          if(isset($_GET['book_id'])){
            $book_id = $_GET['book_id'];
            $book_query = "select * from book where book_id=".$book_id;
            $output = mysqli_query($db, $book_query);
            if($line = mysqli_fetch_assoc($output)) {}
            else header("location:page1.php");
          }
          else header("location:page1.php");

         
         if(isset($_POST['save'])){
         	if($_POST['rating']==0)
         		$rating = "null";
         	else
         		$rating = $_POST['rating'];
         	if($_POST['review']!="")
         		$review = $_POST['review'];
          else $review = null;
         	if(!$_SESSION['row_exists']) {
           // echo "hereskksk";
            if($_POST['year']==0)  //by default
         		   $review_query = "insert into added_book values(null,".$_SESSION['userid'].",$book_id,0,$rating,".$review.",CURRENT_TIMESTAMP)";
               else{
                $timestamp = $_POST['year']."-01-01 00:00:00";
                $review_query = "insert into added_book values(null,".$_SESSION['userid'].",$book_id,0,$rating,".$review.",'".$timestamp."')";
               // echo $review_query;
               }
                 
         	}

         	else {
            if($_POST['year']==0)
         		   $review_query = "update added_book set state=0".",rating=".$rating.",review=".$review.",timestamp=CURRENT_TIMESTAMP where user_id=".$_SESSION['userid']." and book_id=".$book_id;
            else{
              $timestamp = $_POST['year']."-01-01 00:00:00";
              $review_query = "update added_book set state=0".",rating=".$rating.",review='".$review."',timestamp='".$timestamp."' where user_id=".$_SESSION['userid']." and book_id=".$book_id;
              echo $review_query;
            }
               
         	}
         	
         	if(mysqli_query($db, $review_query)){
         		header("location:book.php?book_id=$book_id");
         	}
         }
               

  $action = "rate.php?book_id=".$book_id;
  echo "</head><body>
    <form name='ratingsform' action=$action method='POST' ENCTYPE='multipart/form-data'>  
      <div class='sidenav'>
        <a href='page1.php'>Home</a>";
        if(isset($_SESSION['username'])) {
          echo "<a href='profile.php'>Profile</a>
                <a href='friends.php'>Friends</a>
                <a href='requests.php'>Friend Requests</a>
                <input type='submit' name='logout' class='button' value='Sign Out'>";
          }
        else {
          echo  "<a href='register.php' >Join Us</a>";
        }
         
     echo "</div>
    
      <div class='book' id='bookdiv'>"; 
     
        echo "<table border='0'>";
        echo  "<tr><td rowspan='4' valign='top'><img class='book_img' style='margin-right: 20px;' src='book_pics/".$line['book_image']."'"." alt='avatar'></td></br>";
      
         $authors_query="select * from author,book_author,role where book_author.book_id=".$book_id." and author.author_id=book_author.author_id and book_author.role_id=role.role_id";
         $authors_output = mysqli_query($db, $authors_query);
         $author = "";
          while($line1=mysqli_fetch_assoc($authors_output)){
          	if($line1['role_id'] == 1){              //author
          		$author = $line1['fname']." ".$line1['lname'];
          		break;
          	}
          }
          echo "<td valign='top' align='left' style='padding-left: 3%' colspan='2'><p style='color: maroon; font-size: 30px; margin-bottom:1%'>Write Your Review of '".$line['book_name']."' by ".$author.".</p></br>";
       	  echo "Your Rating &nbsp&nbsp<select name='rating' onchange='view_stars(this)'>
       	  <option value='0'></option>
       	  <option value='1'>1</option>
       	  <option value='2'>2</option>
       	  <option value='3'>3</option>
       	  <option value='4'>4</option>
       	  <option value='5'>5</option> 
       	  </select>&nbsp&nbsp
       	  <span id='stars'></span></br></br>";

        echo "Read in Year: ";
        ?>
        <select name="year">
              <option value="0"> </option>
              <?php
                $current_year = date("Y");
                for($i=1900; $i<=$current_year; $i++){
                  echo "<option value='".$i."' ";
                  echo ">".$i."</option>";
                }
              ?>
            </select></br></br>
        <?php
       	echo "Your Review</br></br><textarea maxlength='2000' name='review'></textarea></br></br>";

       	echo "<input type='submit' value='Save' name='save' class='savebtn'>";
      
        echo "</td></tr></table>";

    
        
       
        echo  "</div>";
     
        if(isset($_POST['logout'])) {
         //unset($_SESSION['username']);
          $_SESSION['signed_out'] = true;
          header("location:login.php");
        }
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
    </form>
  </body>
</html>
  