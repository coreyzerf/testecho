<?php
	$title = "Registration";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	
	$date = date('Y-m-d h:i:s');
	$ready = 0;
	
	if (isset($_POST['reset'])){
		$ready = 0;
		$newpass = $_POST['reset'];
		$encpass = password_hash($newpass, PASSWORD_DEFAULT);
		$id = $_POST['userid'];
		$uniq = $_POST['uniq'];
		if (pwverify($newpass)){
			$query = "UPDATE EchoPeople SET password='$encpass' WHERE id='$id'";
			$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
			if($result){
				$_SESSION['smsg'] = "Password update successfully";
				$query = "DELETE FROM `passReset` WHERE uniq='$uniq'";
				$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
			} else {
				$_SESSION['fmsg'] = "Something has gone wrong";
				addLog("debug:forgot," . $num . ",no result...");
			}
		} else {
			$_SESSION['fmsg'] = "Password must be at least 8 characters and contain 1 number";
		}
	} elseif (isset($_GET['id'])){
		$uniq = $_GET['id'];
		$query = "SELECT * FROM `passReset` WHERE uniq='$uniq'";
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
		$num = $result->num_rows;
		if( $num > 0){
			$row = $result->fetch_assoc();
			$ready = 1;
			$id = $row['id'];
		} else {
			$_SESSION['fmsg'] = "Something has gone wrong";
		}
	} elseif (isset($_POST['input'])){
		$input = $_POST['input'];
		$input = filter_input(INPUT_POST, 'input', FILTER_SANITIZE_STRING);
		if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['fmsg'] = "Sorry, this form can only accept usernames. To recover you username, please click this link: <a href=\"forgotuser.php\">Forgot Username</a>";
		} else {
			$querycamper = "SELECT * FROM `EchoPeople` WHERE username='$input'";
			$resultcamper = mysqli_query($connection, $querycamper) or die(mysqli_error($connection));
			
			if($resultcamper->num_rows != 0){
				$rowcamper = $resultcamper->fetch_assoc();
				$uniq = generateRandomString();
				$id = $rowcamper['id'];
				$first = $rowcamper['first'];
				$last = $rowcamper['last'];
				$email = $rowcamper['email'];
				
				$query = "INSERT INTO `passReset` (id,uniq) VALUES ('$id','$uniq');";
				$result = mysqli_query($connection, $query);
				
				$actual_link = "https://$_SERVER[HTTP_HOST]/"."forgot.php?id=" . $uniq;
				$toEmail = $email;
				$subject = "Password Reset | Echolakecamp.ca";
				$content = "
				<html>
				<body>
				<p>Hey " . $first . " " . $last . "!</p>
				<p>Please click this link to reset your password!.</p>
				<p>" . $actual_link . "</p>
				<p>If you didn't request this, you can safely ignore.</p>
				<p>Thanks,</p>
				<p>Echo Lake Staff</p>
				";
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
				$headers .= 'From: Echo Lake Staff <no-reply@echolakecamp.ca>' . "\r\n";
				if(mail($toEmail, $subject, $content, $headers, "-f no-reply@echolakecamp.ca")) {
					$_SESSION['wmsg'] = "We have sent you an email. Please click the link to reset your password.";	
					addLog("forgot:" . $title . "," . $id . "," . $input . " forgot their password. Email sent to " . $toEmail);
				}
			}else{
				$_SESSION['wmsg'] = "We have sent you an email. Please click the link to reset your password.";
			}
		}
		} else {
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
	}
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
<div class="container medium content">
	<hr>
	<form class="form-signin" method="POST">    
		<h2 class="form-signin-heading">Forgot Password</h2>
		<?php
			if (!$ready){
				echo '<p>Please provide your username for the account you may have forgotten the password for</p>';
				echo '<input type="text" name="input" class="form-control" placeholder="Username"  required autofocus>';
				echo '<button class="btn btn-primary btn-block btn-ered" type="submit">Submit</button>';
			} elseif ($ready){
				echo '<p>Please enter a new password</p>';
				echo '<input type="password" name="reset" class="form-control" placeholder="New Password"  required autofocus>';
				echo '<input type="hidden" name="userid" value="' . $id . '">';
				echo '<input type="hidden" name="uniq" value="' . $uniq . '">';
				echo '<button class="btn btn-primary btn-block btn-ered" type="submit">Submit</button>';
			}
		?>
	</form>
	<hr>
</div>
<?php 
	include('footer.php');
?>