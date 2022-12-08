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
	$query = "SELECT * FROM `camps` WHERE UNIX_TIMESTAMP(date) >= UNIX_TIMESTAMP(DATE(NOW()))";
	$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	if($result == 0){
		$_SESSION['wmsg'] = "There are no active camps";
	}
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<h2>Camp Administration</h2>
		<div class="row">
			<div class="col-md-6">
				<table class="diff">
				<?php
					while($row = $result->fetch_assoc()){
						$campid = $row['campid'];
						$season = $row['season'];
						echo '<tr>';
						echo '<form class="form-signin" method="POST" action="./edit.php">';
						echo "<td class='h'>Season: " . $row['season'] . " Date: " . $row['date'] . " Registered: " . $row['registered'] . " Paid: " . $row['collected'] . "</td>";
						echo "<td class='s'><button class=\"btn btn-primary btn-ered\" name=\"edit\" value=\"" . $campid ."\" type=\"submit\">Edit Camp </button></td>\n";
						echo "</form>";
						echo '</tr>';
					}
				?>
				</table>
			</div>
			<div class="col-md-6">
				<form class="form-signin" method="POST">
					<a class="btn btn-primary btn-block btn-ered" href="create.php">Create New Camp</a>
					<a class="btn btn-primary btn-block btn-ered" href="camppast.php">Past Camps</a>
				</form>
			</div>
		</div>
		<hr>
		<a class="btn btn-primary btn-ered" href="admin.php">Back</a>
		<hr>
	</div>
<?php
	require('footer.php');
?>