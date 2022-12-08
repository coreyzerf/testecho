<?php
	$title = "Registration";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	
	$date = date('Y-m-d h:i:s');
	
	if (isset($_POST['reset'])){
		$newpass = $_POST['reset'];
		$encpass = password_hash($newpass, PASSWORD_DEFAULT);
		$username = $_SESSION['username'];
		if (pwverify($newpass)){
			$query = "UPDATE EchoPeople SET password='$encpass' WHERE username='$username'";
			$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
			$num = $result->num_rows;
			if($result){
				$_SESSION['smsg'] = "Password update successfully";
			} else {
				$_SESSION['fmsg'] = "Something has gone wrong";
			}
		} else {
			$_SESSION['fmsg'] = "Password must be at least 8 characters and contain 1 number";
		}
	} 			
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
<div class="container medium content">
	<hr>
	<form class="form-signin" method="POST">    
		<h2 class="form-signin-heading">Change Password</h2>
		<?php
			echo '<p>Please enter a new password</p>';
			echo '<input type="password" name="reset" class="form-control" placeholder="New Password"  required autofocus>';
			echo '<button class="btn btn-primary btn-ered" type="submit">Submit</button>';
		?>
	</form>
	<hr>
	<a class="btn btn-primary btn-ered" href="./profile.php">Back</a>
	<hr>
</div>
<?php 
	include('footer.php');
?>