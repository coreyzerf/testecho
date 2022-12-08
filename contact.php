<?php
	$title = "Contact";
	require('header.php');

	$regexurl = "((https?|ftp)\:\/\/)?"; // SCHEME 
	$regexurl .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
	$regexurl .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
	$regexurl .= "(\:[0-9]{2,5})?"; // Port 
	$regexurl .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
	$regexurl .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
	$regexurl .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
	
	$regexwords = "(bitcoin|btc|ethereum|seo|explainer|backlinkpro|dofollow";

	if (isset($_POST['save'])){
		$contact = $_POST['contact'];
		$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
		$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
		$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
		$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
	if (!empty($_POST['website'])){
		$_SESSION['fmsg'] = "Error: You are not authorized to use this service. Reason: Suspected Spam";
		addLog("email:" . $title . "," . $username . ", spam caught from " . $email . "," . $name . ".");
	}elseif (preg_match_all("/^$regexwords$/i", $message)){
		$_SESSION['fmsg'] = "Error: You are not authorized to use this service. Reason: Unauthorized words in message";
		addLog("email:" . $title . "," . $username . ", spam caught from " . $email . "," . $name . ".");
	}elseif (preg_match_all("/^$regexurl$/" , $message)){
		$_SESSION['fmsg'] = "Error: You are not authorized to use this service. Reason: URL in message.";
		addLog("email:" . $title . "," . $username . ", spam caught from " . $email . "," . $name . ".");
	}else{
		if ($contact == "null"){
			header('Location: index.php');
		} else {
		if ($contact == "regist"){
			$toEmail = "registrar@Echolakecamp.ca";
			$staff = "Susan Zerf";
		}elseif ($contact == "rental"){
			$toEmail = "rentals@Echolakecamp.ca";
			$staff = "Rebecca Kennedy";
		}elseif ($contact == "summer"){
			$toEmail = "summermanager@Echolakecamp.ca";
			$staff = "James McConnell";
		}elseif ($contact == "weeken"){
			$toEmail = "weekendmanager@Echolakecamp.ca";
			$staff = "Scott Kennedy";
		}elseif ($contact == "websit"){
			$toEmail = "admin@Echolakecamp.ca";
			$staff = "Corey Zerf";
		}elseif ($contact == "promot"){
			$toEmail = "promotions@Echolakecamp.ca";
			$staff = "Corey Peters";
		}elseif ($contact == "execut"){
			$toEmail = "executivedirector@Echolakecamp.ca";
			$staff = "Brent Brown";
		}
		$appendedsubject = "[Contact Form @ Echolakecamp.ca] " . $subject;
		$content = "
		<html>
		<body>
		<p>Hey " . $staff . "</p>
		<p>". $name . "(" . $email . ") submitted a message to the contact form on the Echo Lake website. <br>
		The message can be found below.<br></p>
		<hr>
		<p>" . nl2br($message) . "</p>
		<br>
		<hr>
		<p>You can click <a href=\"mailto:".$email."?Subject=RE:".$subject."\" target=\"_top\">here</a> to reply. If not, you can contact <a href=\"mailto:corey@zerf.ca?Subject=FWD:".$subject."\" target=\"_top\">Corey</a>.
		<p>Thanks,</p>
		<p>Echolakecamp.ca Website</p>
		";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
		$headers .= 'From: Echolakecamp.ca<admin@echolakecamp.ca>' . "\r\n";
		$headers .= 'Reply-To: ' . $email . "\r\n";
		if(mail($toEmail, $appendedsubject, $content, $headers)) {
			$_SESSION['smsg'] = "Your email has been sent.";
			addLog("email:" . $title . "," . $username . ", email sent to " . $toEmail . " from " . $email . "," . $name . ". This was the message: " . $message);
		}else{
			$_SESSION['fmsg'] = "Your email has not been sent.";
		}
		unset($_POST);
		}
	}
}
	
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<h2>CONTACT US</h2>
		<p>
			1055 Bush Rd<br>
			Godfrey, ON K0H 2K0<br>
			<br>
			<h3>In Case of Emergency:</h3>
			<h4>(613) 572-7507</h4>
			<!--(613) 374-5727<br>
			*This number is not used except when camp is in session. -->
		</p>
		<hr>
		<h3>For Any Inquiries, Please Fill out The Form Below.</h3>
		<form class="form-signin" method="POST">
			<input type="text" id="website" name="website" autocomplete="off" style="display: none;">
			<label for="name">Name:</label>
			<input type="text" name="name" class="form-control">
			<label for="email">Email Address:</label>
			<input type="text" name="email" class="form-control">
			<label for="subject">Subject:</label>
			<input type="text" name="subject" class="form-control">
			<label for="contact">Contact:</label>
			<select class="form-control" name="contact" class="form-control">
				<option value="null">Choose an Option</option>
				<option value="regist">Registrar</option>
				<option value="rental">Rental Coordinator</option>
				<option value="summer">Summer Camp Manager</option>
				<option value="weeken">Weekend Camp Manager</option>
				<option value="execut">Executive Director</option>
				<option value="promot">Promotions</option>
				<option value="websit">Website Administrator</option>
			</select>
			<label for="message">Message:</label>
			<textarea name="message" id="message" cols="30" rows="10" class="form-control"></textarea>
			<button name="save" class="btn btn-primary btn-ered" type="submit">Send</button>
		</form>
		<hr>
	</div>
<?php
	require('footer.php');
?>