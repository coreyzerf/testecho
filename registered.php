<?php
	$title = "Registration";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	
	$today = time();
	
	if($_POST['friend']){
		$id = $_SESSION['id'];
		$campid = $_SESSION['campid'];
		$friend = $_POST['friend'];
		$query = "UPDATE `$campid` SET friend='$friend' WHERE camperid='$id'";
		$result = mysqli_query($connection, $query);
        if($result){
            $_SESSION['smsg'] = "Roommate request saved.";
			header('Location: index.php');
		}else{
            $_SESSION['fmsg'] = "Roommate request failed, " . mysqli_error($connection);
		}
		$registered = 1;
	}elseif (isset($_POST['method']) or isset($_POST['txn_id'])){
		if (isset($_POST['method'])){
			$method = $_POST['method'];
			addLog("debug:registered," . $method);
		} else {
			$method = "Paypal";
			addLog("debug:registered," . $method);
		}
		if ($method == "manual"){
			$musername = $_POST['camper'];
			$querycamper = "SELECT * FROM `EchoPeople` WHERE username='$musername'";
			$campid = $_POST['campid'];
		}else{
			$username = $_SESSION['username'];
			$querycamper = "SELECT * FROM `EchoPeople` WHERE username='$username'";
			$campid = $_SESSION['campid'];
		}
		
		addLog("debug:registered,Is the method not Paypal?");
		if ($method != "Paypal"){
			$querycamp = "SELECT * FROM `camps` WHERE campid='$campid'";
			$resultcamper = mysqli_query($connection, $querycamper) or die(mysqli_error($connection));
			$resultcamp = mysqli_query($connection, $querycamp) or die(mysqli_error($connection));
			if($resultcamp->num_rows == 0){
				$_SESSION['wmsg'] = "There are no active camps";
			}
			if($resultcamper->num_rows == 0){
				$_SESSION['wmsg'] = "Something went wrong.";
			}
			$camper = $resultcamper->fetch_assoc();
			$camp = $resultcamp->fetch_assoc();			
			$id = $camper['id'];
			$first = $camper['first'];
			$last = $camper['last'];
			$email = $camper['email'];
			$season = $camper['season'];
			if (isset($_GET['amt'])){
				$price =  (int)$_GET['amt'];
				$item = $_GET{'item_name'};
				$transaction = $_GET{'tx'};
			}elseif ($method == "manual"){
				$price = 0;
				$friend = "";
			}else{
				$price = 0;
			}
			$friend = '';
			$query = "INSERT INTO `$campid` (camperid,amtpaid,friend,registered) VALUES ('$id','$price','$friend',now());";
			$result = mysqli_query($connection, $query);
			addLog("query:" . $title . "," . $username . "," . $query);
			if($result){
				$_SESSION['smsg'] = "Successfully Registered.";
				$aregistered = 1;
			}else{
				$_SESSION['fmsg'] = "Registration Failed, " . mysqli_error($connection);
				$aregistered = 0;
			}
			if ($aregistered) {
				$attended = $camper['attended'] + 1;
				$query = "UPDATE EchoPeople SET attended='$attended' WHERE username='$username'";
				$result = mysqli_query($connection, $query);
				addLog("query:" . $title . "," . $username . "," . $query);
				
				$registered = $camp['registered'] + 1;
				$query = "UPDATE camps SET registered='$registered' WHERE campid='$campid'";
				$result = mysqli_query($connection, $query);
				addLog("query:" . $title . "," . $username . "," . $query);
				
				$collected = $camp['collected'] + $price;
				$query = "UPDATE camps SET collected='$collected' WHERE campid='$campid'";
				$result = mysqli_query($connection, $query);
				addLog("query:" . $title . "," . $username . "," . $query);			
				
				$toEmail = $email;
				$subject = "Registered! | Echolakecamp.ca";
				$content = "
				<html>
				<body>
				<p>Hey " . $first . " " . $last . "!</p>
				<p>You've recieved this email because you have successfully registered for " . $season . " Camp!</p>
				<p>You can expect a acceptance letter soon with more details!</p>";
				if ($method == "cheque"){
					$content .= "<br/>
				<p>In the meantime, please make your cheque out to \"Echo Lake Youth Ministries\", and mail it to:</p>
					<p><b>Susan Zerf<br />1616 Edenwood Dr<br />Oshawa, Ont<br />L1G 7Y6</b></p>";
				}elseif ($method == "manual"){
					$content .= "<br/>
					<p>You were manually registered by " . $_SESSION['username'] . "</p>
					<p>In the meantime, please make your cheque out to \"Echo Lake Youth Ministries\", and mail it to:</p>
					<p><b>Susan Zerf<br />1616 Edenwood Dr<br />Oshawa, Ont<br />L1G 7Y6</b></p>";
				} 
				$content .= "<br />
				<p>Thanks,</p>
				<p>Echo Lake Staff</p>
				";
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
				$headers .= 'From: Echo Lake Staff <registrar@echolakecamp.ca>' . "\r\n";
				if(mail($toEmail, $subject, $content, $headers, "-f registrar@echolakecamp.ca")) {
					$_SESSION['smsg'] .= "<br />We have sent a confirmation email";
					addLog("registered:" . $title . "," . $username . ",email sent to " . $toEmail);
				}
				
				$toAdmin = "echoregistrar@gmail.com";
				$subject = $first . " " . $last . " has registered! | Echolakecamp.ca";
				$content = "
				<html>
				<body>
				<p>Hey!</p>
				<p>You've recieved this email because " . $first . " " . $last . " has registered!</p>";
				if ($method == "cheque"){
					$content .= "<br/>
					<p>They have indicated that they will pay with cheque. You may want to follow up with them at " . $email . "</p>";
				}elseif ($method == "manual"){
					$content .= "<br/>
					<p>" . $_SESSION['username'] . " manually registered them.</p>";
				}
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
				$headers .= 'From: Echo Lake Staff <echo@zerf.ca>' . "\r\n";
				mail($toAdmin, $subject, $content, $headers, "-f echo@echolakecamp.ca");
				addLog("registered:" . $username . ",email sent to " . $toAdmin);
				
				$query = "SELECT * FROM `EchoPeople` ORDER BY `last`, `first`";
				$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
				addLog("query:" . $title . "," . $title . "," . $username . "," . $query);
					if($result->num_rows == 0){
						$_SESSION['wmsg'] = "Something Broke.";
						addLog("error:" . $title . "," . $username . ",query returned no results.");
					}
			}
		}else{
			$querycamper = "SELECT * FROM `EchoPeople` WHERE username='$username'";
			$resultcamper = mysqli_query($connection, $querycamper) or die(mysqli_error($connection));
			if($resultcamper->num_rows == 0){
				$_SESSION['wmsg'] = "Something went wrong.";
			}
			$camper = $resultcamper->fetch_assoc();
			$campid = $_SESSION['campid'];
			$id = $camper['id'];
			addLog("debug:registered,sleeping," . $id);
			sleep(10);
			addLog("debug:registered,waking," . $id);
			if (isregistered($connection, $campid, $id)) {
				$aregistered = 1;
			}
		}
		$_GET['st'] = "";
		unset($_SESSION['manual']);
	}
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<?php
			if ($aregistered){
				echo '<h3>Registered!</h3>';
				if ($method == "cheque"){
					echo "<p>Great! Please make your cheque out to \"Echo Lake Youth Ministries\", and mail it to:</p>";
					echo "<p><b>Susan Zerf<br>";
					echo "1616 Edenwood Dr<br>";
					echo "Oshawa, Ont<br>";
					echo "L1G 7Y6</b></p>";
				}elseif ($method == "manual"){
					echo "<br><p>Great! They are all registered!</p>";
					echo "<br><p>WARNING: Roommate request does not work for manually registered campers</p>";
				}else{
					echo "<br><p>Great! You are all registered! You should recieve confirmation from our registrar a couple weeks before camp starts</p>";
				}
				echo "";
				?>
				<form class="form-signin" method="POST">
				<table>
					<tr><td class="h">Roommate Request:</td>
					<td>
						<?php
						echo "<input list=\"friends\" class=\"form-control\" name=\"friend\" autocomplete=\"off\">";
						echo "<datalist id=\"friends\">";
							while($row = $result->fetch_assoc()){
								$cuserid = $row['id'];
								$cfirst = $row['first'];
								$clast = $row['last'];
								echo "<option data-value=\"" .$cuserid . "\" >" . $cfirst . " " . $clast . "</option>\n";
							}
						echo "</datalist>";
						?>
					</td></tr></table>
					<button name="sfriends" class="btn btn-primary btn-ered" type="submit">Save</button>
					</form>
			<?php
			}else{
				echo '<h3>UH-OH!</h3>';
				echo "<br><p>Something has gone wrong. But never fear, everything can be fixed! Please try again, or contact echoregistrar@gmail.com</p>";

				foreach ($_POST as $key => $value) {
					echo '<p>'.$key.'</p>';
					foreach($value as $k => $v) {
						echo '<p>'.$k.'</p>';
						echo '<p>'.$v.'</p>';
						echo '<hr />';
					}
				} foreach ($_SESSION as $key => $value) {
					echo '<p>'.$key.'</p>';
					echo '<p>'.$value.'</p>';
				}
			}
		?>
		<hr>
		<a class="btn btn-primary btn-ered" href="index.php">Home</a>
		<hr>
	</div>
<?php
	require('footer.php');
?>