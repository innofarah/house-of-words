<html>
  <head>
    <style>
      body{
        background-image: url('BdEayK-01.jpeg');
        opacity:0.7;
        min-height: 100%;
        position:relative;
        scrolling : none;
        background-position: center;
        background-size: cover;
      }
      .all{
        background-color:#f9c25f;
      	opacity: 0.6;		
      	width:20%;
      	margin-left:15%;
      	border-radius:5%;
      	padding:8% 5% ;
      }
      #loader {
        position: absolute;
        left:30%;
        z-index: 1;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
      }
      @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

/* Add animation to "page content" */
      .animate-bottom {
        position: relative;
        -webkit-animation-name: animatebottom;
        -webkit-animation-duration: 1s;
        animation-name: animatebottom;
        animation-duration: 1s
      } 
      @-webkit-keyframes animatebottom {
        from { bottom:-100px; opacity:0 } 
        to { bottom:0px; opacity:1 } 
      }     

      @keyframes animatebottom { 
        from{ bottom:-100px; opacity:0 } 
        to{ bottom:0; opacity:1 }
      }
    
      #myDiv{
        display: none;
        text-align: center;
      }
    </style>
  </head>
  <body onload="myFunction()">
    <?php
      session_start();
    ?>
    <form class='all'>
      <div id="loader"></div>
      <div style="display:none;" id="myDiv" class="animate-bottom">
        <?php
          if(isset($_SESSION['code_verified'])){
            echo "<h3 style='color:white'>You're account is not blocked anymore</h3>";
            //unset($_SESSION['code_verified']);
          }
          else if(isset($_SESSION['password_changed'])){
            echo "<h3 style='color:white'> Password changed </h3>";
            //unset($_SESSION['password_changed']);
          }
          session_destroy();
        ?>
        <p><a href='login.php'>Sign in again</a> </p>
      </div>
    </form>

  <script>
    var myVar;
    function myFunction() {
      myVar = setTimeout(showPage, 5000);
    }

    function showPage() {
      document.getElementById("loader").style.display = "none";
      document.getElementById("myDiv").style.display = "block";
    }
  </script>
  </body>
</html>