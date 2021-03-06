<?php session_start();
?>
<body>

<link rel="stylesheet" type="text/css" href="side-content.css">
<div class="main-content">

	<div class="sidebar">
		<ul>
				<li> <a href="/index.php" title="Back to home"> <img src="img/home.svg">	</a>	</li>
				<li> <a href="/history.php" title="See the history"> <img src="img/history.svg">	</a>	</li> 
				<li> <a href="/candidates.php" title="Who are the candidates ?"> <img src="img/candidates.svg"> </a>	</li> 
				<li> <a href="/rules.php" title="Read the rules">	<img src="img/rules.svg"> 		</a>	</li> 
				<li> <a href="/sign-up.php" title="Sign yourself here"> <img src="img/sign-up.svg">		</a>	</li> 
				<li> <a href="/log-in.php" title="Get in here"> <img src="img/log-in.svg">	</a>	</li> 
				<li> <a href="/vote.php" title="Vote! here"> <img src="img/vote.svg">	</a>	</li>
				<li> <a href="/about.php" title="About this web"> <img src="img/about.svg"> 		</a>	</li> 
				<?php
 		 		 		if(isset($_SESSION["id"])) {

				echo "<li> <a href='/logout.php' title='Log out'> <img src='img/logout.svg'> 		</a>	</li>";
				}	
				?> 	
			</ul> 
			<br><br>
			<!-- CONTACT PERSON -->
			
			<div class="contact">
				<div1>
				<strong> CONTACT US	</strong>	<br>
			</div1>
			<p>	Sekretariat ARC ITB 			<br>
				Sunken Court W-05				<br>
				E-mail :<a href="mailto:pemilu@arc.itb.ac.id"> pemilu@arc.itb.ac.id 	</a><br>
			</p>
			</div>
	</div>

 	<div class="content">
 		<p> You are here : Home > <strong> Log in </strong>	
 		<div class="login">
 			<h1> LOG IN
 			</h1>
 			<!-- SCRIPT UNTUK LOGIN -->
 			<script>
function reload() {
    location.reload();
}
</script>


</head>
<body>
<?php

?>
<?php
if(file_exists("sql.php")){
	require 'sql.php';
} else {
	die("Error");
}
if(!isset($_SESSION["id"])) {
?>
	<?php
		
		$message = "";
		$username = $password = "";
		$usernameErr = $passwordErr = "";
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
			function test_input($data) {
			   $data = trim($data);
			   $data = stripslashes($data);
			   $data = htmlspecialchars($data);
			   return $data;
			}
			
			if (empty($_POST["username"])) {
			 $usernameErr = "username harus diisi";
		   } else {
			 $username = test_input($_POST["username"]);
		   }
		   
		   if (empty($_POST["password"])) {
			 $passwordErr = "password harus diisi";
		   } else {
			 $password = $_POST["password"];
			 $password = md5($password);
		   }
		
			/*$con = mysqli_connect("$host","$user","$passdb","$db");
			
			if (mysqli_connect_errno()){
			  echo "Failed to connect to MySQL: " . mysqli_connect_error();
			  }

			// code for prevent SQL Injection is here...
			$username = mysqli_real_escape_string($con, $_POST['username']);
			//echo $password;
			$result = mysqli_query($con,"SELECT * FROM user WHERE username = '$username' and password = '$password'");
			//$row  = mysql_fetch_array($result);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			if(is_array($row)) {
				$_SESSION["id"] = $row["id"];
				echo $_SESSION["id"];
				$_SESSION["username"] = $row["username"];
				$_SESSION["hasVoted"] = $row["hasVoted"];
				$_SESSION["active"] = $row["active"];
			} else {
				
			}
			if(isset($_SESSION["id"])) {
				
				header("Location:vote.php");
				
			}
			*/

			$conn = new mysqli($host, $user, $passdb, $db);

			if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			}

			$stmt = $conn->prepare("SELECT id, hasVoted, active FROM user WHERE username=? AND password=?");
			$stmt->bind_param('ss', $username, $password);

			$stmt->execute();
			//$result = $stmt->get_result();
		    $stmt->bind_result($id_, $hasVoted_, $active_);

			/* fetch values */
			if ($stmt->fetch())
			{
				echo("Login success");
				$_SESSION["id"] = $id_;
				$_SESSION["username"] = $username;
				$_SESSION["hasVoted"] = $hasVoted_;
				$_SESSION["active"] = $active_;
				header("Location:vote.php");
			}
			else
			{
				$message = "Invalid Username or Password!";
			}
			$stmt->close();
			
			
		}
	?>
	<div class="login-form">
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<table>
		<tr><td width="80">Username:</td><td><input width="250" type="text" required placeholder="Username" name="username" value="">
		</td></tr>
		<tr><td>Password:</td><td><input type="password" required placeholder="Password" name="password" value="">
		</td></tr>
		</table>
		<?php echo $message; ?>
		<input type="submit" name="submit" value="Submit"><input type="reset" value="Reset!">
	</form>
	</div>
	<a href="forget-pass.php" title="klik untuk reset password">Lupa password?</a>
</body>
<?php } else {
	echo "Anda sudah login";
}?>
 			<!-- SCRIPT SELESAI UNTUK LOGIN -->	
 		</div>
	</div>

</div>
</body>
