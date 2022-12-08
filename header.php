<?php
	require('connect.php');
	require('functions.php');
	//
	session_start();
	
	$maint = true;
	$_SESSION['iscovid'] = true;
	
	$isadmin = false;
	
	$page = $_SERVER['PHP_SELF'];
	$now = time(); // or your date as well
	$query = "SELECT date FROM `camps` WHERE date > CURRENT_DATE() ORDER BY CAST(date as datetime) ASC";
	$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	$date = $result->fetch_assoc();
	$your_date = strtotime($date['date']);
	$datediff = $your_date - $now;	
	$until = ceil($datediff / (60 * 60 * 24));
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
		// last request was more than 30 minutes ago
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
		session_start();
		$_SESSION['wmsg'] = "You have been logged out";
	}
	$_SESSION['LAST_ACTIVITY'] = time();
	
	if (isset($_SESSION['username'])){
		$username = $_SESSION['username'];
		$verify = acctverify($username);
		if ($verify){
			$query = "SELECT * FROM `EchoPeople` WHERE username='$username'";
			$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
			$row = $result->fetch_assoc();
			$isadmin = $row["isstaff"];
			$_SESSION["first"] = $row["first"];
			$_SESSION["last"] = $row["last"];	
			unset($row);			
		}
	} else {
		$verify = 0;
	}
	ini_set('SMTP','zerf.ca');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title?> - Echolakecamp.ca</title>	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="./css/custom.css">
		
	<script>
		function goBack() {
			window.history.back();
		}		
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#myModal").modal('show');
    });

</script>
</head>
<body>
	<div class="container medium">
		<div class="row">
			<div class="col" align="center">
				<a href="index.php" id="logo"><img src="images/els.png" alt="logo"></a>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<ul class="nav nav-tabs justify-content-center">
					<li class="nav-item">
						<a class="nav-link <?php if (strpos($page, 'index') !== false) { echo 'active '; }?>" href="index.php">Home</a>
					</li>	
					<li class="nav-item">
						<a class="nav-link <?php if (strpos($page, 'login') !== false) { echo 'active '; } ?>" <?php if ( $verify ){echo "style=\"display:none;\"";}?> href="login.php">Login</a>
					</li>					
					<li class="nav-item">
						<a class="nav-link <?php if (strpos($page, 'register') !== false) { echo 'active '; } ?>" <?php if ( $isadmin ){echo "style=\"display:none;\"";}?> href="register.php"><?php if (isset($username) ) {echo 'Camps';} else {echo 'Register';}?></a>
					</li>
					<li class="nav-item dropdown" <?php if ( !$isadmin ){echo "style=\"display:none;\"";}?>>
						<a class="nav-link <?php if (strpos($page, 'admin') !== false) { echo 'active '; } ?> dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Admin</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="campadmin.php">Camp Administration</a>
							<a class="dropdown-item" href="camperadmin.php">Camper Administration</a>
							<a class="dropdown-item" href="reports.php">Reports</a>
							<!--<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#">Separated link</a>-->
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if (strpos($page, 'about') !== false) { echo 'active '; } ?>" href="about.php">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if (strpos($page, 'contact') !== false) { echo 'active '; } ?>" href="contact.php">Contact</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if (strpos($page, 'map') !== false) { echo 'active '; } ?>" href="map.php">Map</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if (strpos($page, 'profile') !== false) { echo 'active '; } ?>" <?php if ( !$verify ){echo "style=\"display:none;\"";}?> href="profile.php">Profile</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if (strpos($page, 'donate') !== false) { echo 'active '; } ?>" href="donate">Donate</a>
					</li>
					
					<li class="nav-item" <?php if ( !$verify ){echo "style=\"display:none;\"";}?>>
						<a class="nav-link" href="logout.php">Logout</a>
					</li>
				</ul>
			</div>
		</div>
		
	<!--PHP HEADER END-->
	