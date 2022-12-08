<?php
	$title = "Admin Settings";
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
	}elseif ( isset($_POST['delete'])){
		$delete = $_POST['delete'];
		unset($_POST);
		$query = "DELETE FROM camps WHERE campid = '" . $delete . "';";
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
		if($result){
			$query = "DROP TABLE " . $delete . ";";
			$result = mysqli_query($connection, $query);
			if($result){
				$_SESSION['smsg'] = "Camp Deleted Successfully.";
			}else{
				$_SESSION['fmsg'] ="Deletion Failed " . mysqli_error($connection);
			}
		}else{
			$_SESSION['fmsg'] ="Deletion Failed " . mysqli_error($connection);
		}
	}
	$username = $_SESSION['username'];
	$query = "SELECT * FROM `camps`";
	$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	if($result == 0){
		$_SESSION['wmsg'] = "There are no active camps";
	}
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
<div class="container medium content">
	<hr>
	<h2>Admin Settings</h2>
	<a class="btn btn-primary btn-ered" href="campadmin.php">Camp Administration</a>
	<hr>
	<a class="btn btn-primary btn-ered" href="camperadmin.php">Camper Administration</a>
	<hr>
	<a class="btn btn-primary btn-ered" href="reports.php">Reports</a>				
	<hr>
	<a class="btn btn-primary btn-ered" href="index.php">Back</a>
	<hr>
</div>
<?php
	require('footer.php');
?>