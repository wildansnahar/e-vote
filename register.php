<html>
<head><title></title>
</head>
<body>
<?php
if(file_exists("sql.php")){
	require 'sql.php';
} else {
	die("Error");
}
session_start();
if(isset($_SESSION["id"])){
	echo "Untuk mendaftar akun baru Anda harus sign out terlebih dahulu";
	echo "Klik <a href='signout_reg.php'>di sini</a> untuk melakukannya";
} else {
?>

	<?php

		$email = $pass = $cpass = $name = $angkatan = "";
		$emailErr = $passErr = $cpassErr = $nameErr = $angkatanErr = "";
		$emailOK = $passOK = $cpassOK = $nameOK = $angkatanOK = "";
		
		function test_input($data) {
			$data = trim($data);	
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		function generateRandomString($length = 20) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
		return $randomString;
		}
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			//Email
			if (empty($_POST["email"])) {
				 $emailErr = "Email harus diisi";
			   } else {
				 $email = test_input($_POST["email"]);
				 
				// check email harus @ arc
				if (!preg_match("/@arc.itb.ac.id$/",$email)) {
					$emailErr = "Format e-mail salah"; 
				} else {
					$emailOK = true;
				}
			   }
			 
			 //Nama
			   if (empty($_POST["name"])) {
				 $nameErr = "Name harus diisi";
			   } else {
				 $name = test_input($_POST["name"]);
				 // check if name only contains letters and whitespace
				 if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
				   $nameErr = "Nama hanya boleh berisi huruf atau spasi"; 
				 } else {
					$nameOK = true;
				 }
			   }
			
			//Angkatan
			if (empty($_POST["angkatan"])) {
			 $angkatanErr = "Pilih salah satu angkatan";
			/*echo '<script type="text/javascript">'
			, 'errMessage("Pilih salah satu kategori");'
			, '</script>';
		;*/
		   } else {
				$angkatan = $_POST["angkatan"];
				$angkatanOK = true;
		   }
			
			//Password
			if (empty($_POST["password"])) {
				 $passErr = "Password harus diisi";
			   } else {
				 $pass = htmlspecialchars($_POST["password"]);
				 $pass = md5($pass);
				
				//cekformatpassword
				//if (!preg_match('/^[A-Za-z0-9!@#$%^&*()?]$/',$pass)) {
					//$passErr = "Format password salah"; 
				//} else {
					$passOK = true;
				//}
			   }
			  
			 if(!(($_POST["password"]) == ($_POST["cpassword"]))){
					$cpassErr = "Password tidak sama"; 
			 } else {
				$cpassOK = true;
			 }
			   
			  if($emailOK && $passOK && $cpassOK && $nameOK && $angkatanOK){
					//echo 'zzzzzzzzzzzzz';
					$con = mysqli_connect("$host","$user","$passdb","$db");

					if (mysqli_connect_errno()){
					  echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}

					// echo($host.' '.$user.' '.$passdb.' '.$db);
		
					$result = mysqli_query($con,"SELECT * FROM user WHERE username = '$email'");
					$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
					// var_dump($row);
					if(!(is_array($row))) {
						$token = generateRandomString();
						$sql="INSERT INTO user (username,fullname,angkatan,password,hasVoted,active,regtime,expire,token)
						VALUES ('$email', '$name',$angkatan,'$pass',false,false,NOW(),ADDDATE(NOW(), INTERVAL 1 DAY),'$token')";
						if (!mysqli_query($con,$sql)) {
						  die('Error: ' . mysqli_error($con));
						} else {
							$to = "$email";
							$subject = "Confirm registration";
							$link = "http://pemilu.arc.itb.ac.id/polling/confirm.php?name=".$email."&code=".$token;
							$txt = "Untuk melakukan registrasi. Klik $link";
							$headers = "From: pemilu@arc.itb.ac.id" . "\r\n" .
							"CC: somebodyelse@example.com";

							mail($to,$subject,$txt,$headers);
							header("Location:register_success.php");
						}
					} else {
						//sudah terdaftar
						$emailErr = "email sudah terdaftar";

						echo("$emailErr");
					}
					
					mysqli_close($con);
			}
		}	
	?>
	Register
	<div class="register-form">
		<form name="form-reg" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return chkValidity();">
			<table>
			<tr><td width="80">Nama:</td><td><input width="250" type="text" required placeholder="Nama lengkap" name="name" value=""></td></tr>
			</td><td><span class="error"><?php echo $emailErr;?></td></span></tr>
			<tr><td>Angkatan:</td><td><select name="angkatan" required>
		   <option value="">Pilih salah satu</option>
		   <option value="2011">2011</option>
		   <option value="2012">2012</option>
		   <option value="2013">2013</option>
		   <option value="2014">2014</option>
		   </select></td></tr>
		   <tr><td width="80">Email:</td><td><input width="250" type="text" required placeholder="Gunakan email @arc.itb.ac.id" name="email" value=""></td></tr>
			<tr><td>Password:</td><td><input id="pass1" type="password" required placeholder="Password" name="password" value="" onkeyup="chkPasswordStrength(this.value)">
			</td><td><span class="error"><?php echo $passErr;?></td><td id="strendth" class="strength5">Password Strength</td><td id="error"></td></span></tr>
			<tr><td>Confirm Password:</td><td><input id="pass2" type="password" required placeholder="Confirm password" name="cpassword" value="" onkeyup="checkPass()">
			</td><td><span class="error"><?php echo $cpassErr;?></td><td id="confirmMessage"><td></span></tr>
			</table>
			<input type="submit" name="submit" value="Submit"><input type="reset" value="Reset!">
		</form>
	</div>
		
	<script>
	
	function chkValidity(){
		//nama
		var txtname = document.getElementById('name').value;
		
		var i = 0;
		while(i<txtname.length){
			if(!(txtpass[i].match(/[a-z]/)) && !(txtpass[i].match(/[A-Z]/)) && !(txtpass[i].match(/ /))){
				alert("Format nama salah");
				return false;
				break;
			}
			i++;
		}
	
		//password
		var txtpass = document.getElementById('pass1').value;
		
		if(txtpass.length < 6){
			alert("Password minimal 6 karakter");
			return false;
			//emailok = false;
		}
		
		if(!(txtpass.match(/[a-z]/)) || !(txtpass.match(/[A-Z]/))){
			alert("Password harus mengandung huruf besar dan kecil");
			return false;
			//emailok = false;
		}
		
		if(!(txtpass.match(/.[!,?,@,#,$,%,^,&,*,),(,-,_]/))){
			alert("Password harus mengandung karakter spesial");
			return false;
			//emailok = false;
		}
		
		//confirmpass
		var pass1 = document.getElementById('pass1').value;
		var pass2 = document.getElementById('pass2').value;
		if(pass1 != pass2){
			alert("Password harus match");
			return false;
		}
		
		//email
		var x = document.forms["form-reg"]["email"].value;
		var atPos = x.indexOf("@");
		var dotpos = x.lastIndexOf(".");
		var l = x.length;
		if((x[l-1]!='d') || (x[l-2]!='i') || (x[l-3]!='.')){
			alert("Email harus @arc.itb.ac.id");
			return false;
			//emailok = false;
		}
		if((x[l-4]!='c') || (x[l-5]!='a') || (x[l-6]!='.')){
			alert("Email harus @arc.itb.ac.id");
			return false;
			//emailok = false;
		}
		if((x[l-7]!='b') || (x[l-8]!='t') || (x[l-9]!='i')){
			alert("Email harus @arc.itb.ac.id");
			return false;
			//emailok = false;
		}
		if((x[l-10] != '.') || (x[l-11] != 'c') || (x[l-12] != 'r')){
			alert("Email harus @arc.itb.ac.id");
			return false;
			//emailok = false;
		}
		if((x[l-13]!='a') || (x[l-14]!='@')){
			alert("Email harus @arc.itb.ac.id");
			return false;
			//emailok = false;
		}
		if ((atPos<1) || (dotpos<atPos+2) || (dotPos+2>=x.length)){
			alert("Email tidak valid");
			return false;
			//emailok = false;
		}
		
	}

	function chkPasswordStrength(txtpass){

			var desc = new Array();
			desc[0] = "Sangat lemah";
			desc[1] = "Lemah";
			desc[2] = "Sedang";
			desc[3] = "Kuat";
			desc[4] = "Sangat Kuat";
			var strengthMsg = document.getElementById("strendth");
			var errorMsg = document.getElementById("error");
			errorMsg.innerHTML = "";
			var score = 0;
		
			if(txtpass.length > 6){
				score++;
			}
		
			if((txtpass.match(/[a-z]/)) && (txtpass.match(/[A-Z]/))){
				score++;
			}
			
			if(txtpass.match(/.[!,@,#,$,%,^,&,*,),(,-,_]/)){
				score++;
			}
			
			if(txtpass.length > 12){
				score++;
			}
			
			strengthMsg.innerHTML = desc[score];
			strengthMsg.className = "strength" + score;
			
			if(txtpass.length<6){
				errorMsg.innerHTML = "Password harus minimum 6 karakter";
				errorMsg.className = "error";
			}
	}

	function checkPass(){
		var pass1 = document.getElementById('pass1');
		var pass2 = document.getElementById('pass2');

		var message = document.getElementById('confirmMessage');
		
		var goodColor = "#66cc66";
		var badColor = "#ff6666";
		
		if(pass1.value == pass2.value){
			pass2.style.backgroundColor = goodColor;
			message.style.color = goodColor;
			message.innerHTML = "Password Match!";
		} else {
			pass2.style.backgroundColor = badColor;
			message.style.color = badColor;
			message.innerHTML = "Password Do Not Match!";
		}
	}
	<?php
}
	?>
</script>
</body>
</html>