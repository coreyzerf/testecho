<?php
	$title = "Registration";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	
	if ($isadmin && !isset($_POST['manual'])){
		$_SESSION['fmsg'] = "Staff currently cannot register for camp.";
	}elseif (isset($_POST['register'])){
	}elseif (isset($_POST['manual'])){
		$id = $_POST['manual'];
		$querycamper = "SELECT * FROM `EchoPeople` WHERE id='$id'";
		$querycamp = "SELECT * FROM `camps` WHERE UNIX_TIMESTAMP(date) >= UNIX_TIMESTAMP(DATE(NOW()))";
		$resultcamper = mysqli_query($connection, $querycamper) or die(mysqli_error($connection));
		$resultcamp = mysqli_query($connection, $querycamp) or die(mysqli_error($connection));
		if($resultcamp->num_rows == 0){
			$_SESSION['wmsg'] = "There are no active camps";
		}
		if($resultcamper->num_rows == 0){
			$_SESSION['wmsg'] = "Something went wrong.";
		}
		$camper = $resultcamper->fetch_assoc();
		$musername = $camper['username'];		
		$eligible = eligible($musername, 1);
		$_SESSION['manual'] = $id;
	}

	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<?php
			if (isset($_SESSION['manual'])){
			echo '<h2>REGISTER FOR A CAMP</h2>';
				if (!$eligible){
					echo '<p>You are not eligible to register for camp. Please check the errors and your profile and try again.</p>';
					echo '<a class="btn btn-primary btn-block btn-ered" href="profile.php">Profile</a>';
					echo '<hr>';
					echo '<a class="btn btn-primary btn-ered" href="camperedit.php">Back</a>';
				}else{
					echo '<p>Great! You are eligible to register for the following camps:';
					
						echo '<table>';
							while($rowcamp = $resultcamp->fetch_assoc()){
								echo '<form class="form-signin" method="POST" action="./registered.php" >';
								$campid = $rowcamp['campid'];
								$season = $rowcamp['season'];
								$date = $rowcamp['date'];
								if (isregistered($connection, $campid, $id)) {
									echo '<tr>';
									echo "<td>It would appear that they are already registered for $season ($date) camp.</td>";
									echo '</tr>';
								}else{
									echo '<tr>';
									echo "<td class='h'>" . $season . " Camp " . date("Y", strtotime($date)) . "</td>";
									echo "<input type=\"hidden\" name=\"camper\" value=\"$musername\">";
									echo "<input type=\"hidden\" name=\"campid\" value=\"$campid\">";
									echo "<td><button class=\"btn btn-primary btn-block btn-ered\" name=\"method\" value=\"manual\" type=\"submit\">Register </button></td>";
									echo '</tr>';
								}
								echo "</form>";
							}
						echo "</table>";
					echo '<hr>';
					echo '<a class="btn btn-primary btn-ered" href="index.php">Back</a>';
				}
			}
		?>
		<hr>
	</div>
<?php
	require('footer.php');
?>