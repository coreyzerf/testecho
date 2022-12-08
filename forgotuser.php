<?php
	$title = "Registration";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	
	$date = date('Y-m-d h:i:s');
	$ready = 0;
	
	if (isset($_POST['input'])){
		$email = $_POST['input'];
		$email = filter_input(INPUT_POST, 'input', FILTER_SANITIZE_STRING);
		$querycamper = "SELECT * FROM `EchoPeople` WHERE email='$email'";
		$resultcamper = mysqli_query($connection, $querycamper) or die(mysqli_error($connection));
		
		if($resultcamper->num_rows != 0){
			$username = "| ";
			while($rowcamper = $resultcamper->fetch_assoc()){
				$username .= $rowcamper['username'] . " | ";
			}
			
			$toEmail = $email;
			$subject = "Forgotten Email | Echolakecamp.ca";
			$content = "
			<html>
			<body>
			<p>Hey there!</p>
			<p>Your username is as follows: \"" . $username . "\"</p>
			<p>If you see two or more usernames, you may have 2 or more accounts on our website. This is likely because you have more than one child who has attended the camp.</p>
			<p>If you are unsure as to why you have more than one account, or there is more than one of the same or similar usernames, please contact admin@echolakecamp.ca</p>
			<p>If you didn't request this, you can safely ignore.</p>
			<p>Thanks,</p>
			<p>Echo Lake Staff</p>
			";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
			$headers .= 'From: Echo Lake Staff <admin@echolakecamp.ca>' . "\r\n";
			if(mail($toEmail, $subject, $content, $headers, "-f admin@echolakecamp.ca")) {
				$_SESSION['wmsg'] = "We have sent you an email containing your username.";	
				addLog("forgot:" . $title . "," . $id . "," . $email . " forgot their username. Email sent to " . $toEmail);
			}
		}else{
			$_SESSION['wmsg'] = "We have sent you an email. Please click the link to reset your password.";
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
		<h2 class="form-signin-heading">Forgot Username</h2>
		<p>Please provide the email address you have used for the account</p>
		<input type="text" name="input" class="form-control" placeholder="E-mail"  required autofocus>
		<button class="btn btn-primary btn-block btn-ered" type="submit">Submit</button>
	</form>
	<hr>
</div>
<?php 
	include('footer.php');
?>