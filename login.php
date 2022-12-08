<?php
	$title = "Login";
	session_start();
	
	
	//require('functions.php');
	
	$date = date('Y-m-d h:i:s');
	
	if (isset($_POST['username']) and isset($_POST['password'])){
		require('connect.php');		
		require('functions.php');
		//session_unset();
		//3.1.1 Assigning posted values to variables.
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
		$username = strtolower($username);
		$password = $_POST['password'];
		$encpass = password_hash($password, PASSWORD_DEFAULT);
		//3.1.2 Checking the values are existing in the database or not
		$query = "SELECT * FROM `EchoPeople` WHERE username='$username'";
		 
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
		$count = mysqli_num_rows($result);
		$row = $result->fetch_assoc();
		$encpass = $row["password"];
		$lastlogin = $row['lastlogin'];
		$first = $row['first'];
		$id = $row['id'];
		//3.1.2 If the posted values are equal to the database values, then session will be created for the user.
		if ($count == 1) {
			$activated = $row["isactive"];
			$correct = password_verify($password, $encpass);
			if (!$activated){
				$_SESSION['fmsg'] = "Sorry, your account is not activated. Please check your email for your activation link";
			}elseif($correct == true) {				
				$_SESSION['username'] = $username;
				$_SESSION['id'] = $id;
				$id = $_SESSION['id'];
				$_SESSION['loggedin'] = 1;
				$query = "UPDATE EchoPeople SET lastlogin=now() WHERE id='".$id."';";
				$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
				addLog("query:" . $title . "," . $username . "," . $query . "," . $result);
				
			} else {
				$_SESSION['fmsg'] = "Invalid Login Credentials.";
				addLog("error:" . $title . "," . $username . " had bad credentials");
				header('Location: login.php');
				
			}
		}else{
            echo'<!-- Modal -->';
            echo'<div class="modal fade" id="myModal" role="dialog">';
            echo'	<div class="modal-dialog modal-dialog-centered" role="document">';
            echo'		<div class="modal-content">';
            echo'			<div class="modal-header">';
            echo'				<h5 class="modal-title" id="exampleModalLabel">Welcome to our new website!</h5>';
            echo'				<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo'					<span aria-hidden="true">&times;</span>';
            echo'				</button>';
            echo'			</div>';
            echo'		<div class="modal-body">';
            echo'			<p>Welcome to our new website! Unfortunately, we did not migrate your information in the transition, so you will need to create a new account. Please use the buttons below if you need to create a new account.</p>';
            echo'			<p>If you have created a new account, please use the forgot username link below to recover your old account.</p>';
            echo'		</div>';
            echo'		<div class="modal-footer">';
            echo'			<a class="btn btn-secondary btn-ered" href="./register.php">Create a new account</a>';
            echo'			<a class="btn btn-secondary btn-ered" href="./forgotuser.php">Forgot Username</a>';
            echo'			<button type="button" class="btn btn-secondary btn-ered" data-dismiss="modal">Close</button></button>';
            echo'		</div>';
            echo'	</div>';
            echo'</div>';
        echo'</div>';
			$_SESSION['fmsg'] = "Account not found.";
			addLog("error:" . $title . "," . $username . " was not found");
		}
	}
	//3.1.4 if the user is logged in Greets the user with message
	if (isset($_SESSION['username'])){
		$username = $_SESSION['username'];
		/*echo "<p>" . $username . "</p>";
		echo "<p>This is the Members Area</p>";
		echo "<p>Your UUID is " . $_SESSION['id'] . "</p>";
		echo "<p><a href='logout.php'>Logout</a></p>";*/
		$_SESSION['smsg'] = "Welcome back!<br>";
		addLog("login:" . $title . "," . $username);
		header('Location: index.php');
		 
	}
	include('header.php');
	
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>

<div class="container medium content">
	<hr>
	<form class="form-signin" method="POST">    
		<h2 class="form-signin-heading">Please Login</h2>
		<input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $_POST['username']; ?>"  required autofocus>
		<input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required> 
		<a class="btn btn-link" href="forgotuser.php">Forgot Username?</a>
		<a class="btn btn-link" href="forgot.php">Forgot Password?</a>
		<!--<div class="checkbox">
			<label>
				<input type="checkbox" value="remember-me"> Remember me
			</label>
		</div>-->
		<button class="btn btn-primary btn-block btn-ered" type="submit">Login</button>
		<a class="btn btn-primary btn-block btn-ered" href="register.php">Create an Account</a>
		
	</form>
	<hr>
</div>
<?php 
	include('footer.php');
?>