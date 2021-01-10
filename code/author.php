<html>
  <head>
    <title>House Of Words. Author</title>
    <link rel="stylesheet" type="text/css" href="book_stylesheet.css">
    <?php session_start();
          include 'db_connection.php';     
          if(isset($_GET['author_id'])){
            $author_id = $_GET['author_id'];
            $author_query = "select * from author where author_id=".$author_id;
            $output = mysqli_query($db, $author_query);
            $line = mysqli_fetch_assoc($output);
        //  else header("location:page1.php");
          }
      //  else header("location:page1.php");

    echo "</head><body>
    <form name='bookform' method='POST' ENCTYPE='multipart/form-data'>  
      <div class='sidenav'>
        <a href='page1.php'>Home</a>";
        if(isset($_SESSION['username'])) {
          echo "<a href='profile.php'>Profile</a>
                <a href='friends.php'>Friends</a>
                <a href='requests.php'>Friend Requests</a>
                <input type='submit' name='logout' class='button' value='Sign Out' onclick='document.bookform.action=\"author.php?author_id=$author_id\"'>";   
          }
        else {
          echo  "<a href='register.php' >Join Us</a>";
        }
         
     echo "</div>
      <div class='book' id='bookdiv'>"; //start of center div
       
        echo "<img  src='author_pics/".$line['image']."'"." alt='author_pics/avatar.jpg' style='width:30%; display: inline-block;'>";
        echo "<span>".$line['fname']." ".$line['lname']."</span>";

      echo "</div>";  //end of center div

        echo "<div class='column'>";   //start of quote div
        echo "<div class='card'>";

        echo "<div class='container'>";
          
        echo "</div></div></div>";   //end of quote div


      ?>
    
    </form>
  </body>
</html>
  