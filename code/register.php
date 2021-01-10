<!DOCTYPE html>
<html>
	<head>
		<title>House Of Words. Register</title>
		<style>
			body{ 
				font-family: "Book Antiqua";
		    	background-image: url('BdEayK-01.jpeg');
		    	min-height: 100%;
				position:relative;
				scrolling : none;
		    	background-position: center;
		    	background-size: cover;
			}
			.all{
				opacity:0.4;
				background-color:#f9c25f; 
				width:30%;
				margin-left:25%;
				border-radius:5%;
				padding:8% 5% ;
			}	
			form{
				margin-left:3%;
			}
			h3{	
				font-size: 50px;	
				font-style: oblique;
				font-family: "Times New Roman", Times, serif;
			 }	
		</style>
	</head>
	
	<body>
 		<?php session_start(); 
 		if(isset($_SESSION['username']))
 			header("location:page1.php");
 		include 'db_connection.php'; ?>
		<div class="all">
			<h3>Be a Member</h3>
			<form name="register" method="POST" action="register.php">
				<table>
					<tr><td><label>First Name: </label></td>
						<td><input type="text" name="fname" placeholder="Enter Your First Name" required="true"></td>
					</tr>
					<tr>
						<td><label>Last name: </label></td>
						<td><input type="text" name="lname" placeholder="Enter Your Last Name" required="true"></td>
					</tr>
					<tr>
						<td><label>Email: </label></td>
						<td><input type="text" name="email" placeholder="Enter Your Email" required="true"></td>
					</tr>
					<tr>
						<td><label>Username: </label></td>
						<td><input type="text" name="username" placeholder="Choose Your Username" required="true"></td>
					</tr>
					<tr>
						<td><label>Password:</label></td>
						<td><input type="password" name="password" placeholder="Enter a password" required="true"></td>
					</tr>
					<tr>
						<td>Country: </td>
						<td>
							<select name='country'>
								<option value="0" >Choose Your Country</option>
								<?php
									$query = "select code,name from country";
									$output = mysqli_query($db, $query);
									while($line=mysqli_fetch_assoc($output)){
										echo "<option value='".$line['code']."'>".$line['name']."</option>";
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Date of Birth</td>
           <td> <select name="day">
              <?php
              for($i=1; $i<=31; $i++){
                  echo "<option value='".$i."' ";
                  echo ">".$i."</option>";
                }
                ?>
            </select>&nbsp&nbsp
            <select name="month">
                        </option>
                        <option value="01">Jan</option>
                        <option value="02">Feb</option>
                        <option value="03">Mar</option>
                        <option value="04">Apr</option>
                        <option value="05">May</option>
                        <option value="06">Jun</option>
                        <option value="07">Jul</option>
                        <option value="08">Aug</option>
                        <option value="09">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
            </select>&nbsp&nbsp
            <select name="year">
              <?php
                $current_year = date("Y");
                for($i=1900; $i<=$current_year; $i++){
                  echo "<option value='".$i."' ";
                  echo ">".$i."</option>";
                }
              ?>
            </select>
           </td>
					</tr>
					<tr>
						<td><label>Gender: </label></td>
						<td><select name="gender">
								<option value="M">Male</option>
								<option value="F">Female</option>
							</select></td>
					</tr>
					<tr>
						<td><input type="submit" name="register" value="Sign Up" style="margin-bottom:0% "></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>

