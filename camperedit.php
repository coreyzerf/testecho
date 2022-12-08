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
	
	$query = "SELECT * FROM `EchoPeople` ORDER BY `last`, `first`";
	$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	if($result->num_rows == 0){
		$_SESSION['wmsg'] = "Something Broke.";
	}
	
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<h2>Edit Profile</h2>
		<form class="form-signin" method="POST" action="./camperprofile.php">
			<?php								
				while($row = $result->fetch_assoc()){
					$id = $row['id'];
					//echo "<input type=\"hidden\" name=\"camper\" value=\"$id\">";
					echo "<button class=\"btn btn-link\" name=\"camper\" type=\"submit\" value=\"$id\">" . $row['first'] . " " . $row['last'] . "</button>\n";
				}
			?>
			<hr>
			<a class="btn btn-primary btn-ered" href="newprofile.php">New Camper</a><br><br>
			<a class="btn btn-primary btn-ered" href="camperadmin.php">Back</a>
		</form>
		<hr>
	</div>
<?php
	require('footer.php');
?>