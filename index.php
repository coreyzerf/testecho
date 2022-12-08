<?php


	$title = "Home";
	include('header.php');
	
	echo $maint;
	if( $maint == true and !$isadmin ){header('Location: maint.php');}
	if( $maint == true and !isset($isadmin)){header('Location: maint.php');}
	//require('connect.php');
	//require('functions.php');
	
	$date = date('Y-m-d');
	//$username = $_SESSION['username'];
	//$verify = acctverify($username);
	$first = $_SESSION["first"];
	$last = $_SESSION["last"];
	$condition = "";
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg'],$username);
	
	$querycamp = "SELECT * FROM `camps` WHERE season='Winter'";
	$resultcamp = mysqli_query($connection, $querycamp) or die(mysqli_error($connection));
	if($resultcamp->num_rows == 0){
			$registered = 11;
			$condition = "*";
		}
	while($rowcamp = $resultcamp->fetch_assoc()){
		$registered = $rowcamp['registered'];
	}
	/*if ( !$isadmin or !isset($username) ){
		displayModal("Spring Camp 2022","
		 Spring Camp 2022 is officially a go! 
		 <br>
		 <br>
		 All you need to do right now is register! We will let you know at a later date about how the camp will run.
		 <br>
		 <br>
		 We pray that each of you continue to stay safe - we look forward to being together again very soon.
		 <br>
		 <br>
		 ​Thank you for your understanding,
		 <br>
		Echo Lake Youth Ministries","modal-lg");
	}*/
	?> 
</div>
<div class="container medium" <?php if ( $isadmin ){echo "style=\"display:none\"";}?>>
	<div class="jumbotron justify-content-center" align="center">	
		<span class="display-3">2023 Camps:<br /><span class="display-4">Winter?</span></span>
		<br />
		<span class="lead">Date: February 17-20 <br />Cost: $150 Before January 1st, $175 after <br /> Speaker: TBD </span>
		<br /><span class="display-4">Spring</span></span>
		<br />
		<span class="lead">Date: May 19-22 <br />Cost: $150 Before May 1st, $175 after <br />Speaker: Kirk Perry </span>
		<br /><span class="display-4">Summer</span></span>
		<br />
		<span class="lead">Date: August 20-27 <br />Cost: $450 Before July 30th, $550 after <br /> Speaker: Morgan Mitchell </span>
		<br /><span class="display-4">Fall</span></span>
		<br />
		<span class="lead">Date: October 6-9 <br />Cost: $150 Before September 18th, $175 after <br />Speaker: TBD </span>
		<p class="lead">
		<br /><p class="lead">Questions about registration costs? <a href="./costs.php">Registration Costs Explained</a></p>
		<p class="lead">Registration Questions? Check out our <a href="./about.php">FAQ</a> or <a href="mailto:echoregistrar@gmail.com"> Please contact us!</a></p>
		<p class="lead">For any other questions or concerns please contact <a href="mailto:corey@thepetersfamily.ca">Corey</a></p>
		<a class="btn btn-primary btn-ered" href="login.php" <?php if ( $verify ){echo "style=\"display:none\"";}?>>Login Now!</a>	
				
	</div>
</div>
<div class="container medium content">
	<hr>
	<div class="row">
		<div class="col-xl-2 col-lg-4" <?php if ( !$verify ){echo "style=\"display:none;\"";}?>>
			<h3>Hi, <?php echo $first . " " . $last; ?>!</h3>
			<?php if ( $isadmin ){echo "<p><a class=\"btn btn-primary btn-ered\" href=\"admin.php\">Admin Settings</a></p>";} ?>
			<?php if ( !$isadmin ){echo "<p><a class=\"btn btn-primary btn-ered\" href=\"register.php\">Register for camp</a></p>";} ?>
			<p><a class="btn btn-primary btn-ered" href="profile.php">Edit your profile</a></p>
			<p><a class="btn btn-primary btn-ered" href="logout.php">Logout</a></p>
			<hr> 
		</div>

		<div class="<?php if ( !$verify ){echo "col";} else {echo "col-xl-10 col-lg-8";}?>">
			<h2>Echo Lake Camp
				<small class="text-muted">We Missed You.</small>
			</h2>
			<hr>
			
			<p>NEW! You can now send an e-transfer to pay for camp! Details when you register!</p>
		</div>
	</div>
	<hr>
</div>
<?php
	require('footer.php');
?>