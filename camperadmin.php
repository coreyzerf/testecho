<?php
	$title = "Admin Settings - Echolakecamp.ca";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	
	$date = date('Y-m-d');
	if ( !acctverify($_SESSION['username'])){
	session_unset();
	$_SESSION['fmsg'] = "You are not logged in.";
	header('Location: index.php');
	}elseif (!$isadmin){
		header('Location: index.php');
	}
	
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<h2>Camper Administration</h2>
		<a class="btn btn-primary btn-ered" href="newprofile.php">New Camper</a>
		<a class="btn btn-primary btn-ered" href="camperedit.php">Edit Profiles</a><br><hr>
		<a class="btn btn-primary btn-ered" href="admin.php">Back</a>
		<hr>		
	</div>
<?php
	require('footer.php');
?>