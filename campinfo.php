<?php
	$title = "Registration";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	if ( !acctverify($_SESSION['username'])){
		session_unset();
		$_SESSION['fmsg'] = "You are not logged in.";
		header('Location: login.php');
	}elseif (!$isadmin){
		header('Location: index.php');
	}
	$campid = $_POST['campid'];
	
	if (isset($_POST['save'])){
		$dothis = $_POST['save'];
		if ($dothis == "save"){
			foreach ($_POST['id'] as $id){
				$paid = $_POST['paid'][$id];
				$query = "UPDATE " . $campid . " SET amtpaid='$paid' WHERE camperid='$id';";
				$result = mysqli_query($connection, $query);
				if($result){
					$_SESSION['smsg'] = "Saved.";
				}else{
					$_SESSION['fmsg'] = "Save failed, " . mysqli_error($connection);
				}
			}
		} elseif ($dothis == "delete") {
			$querycamper = "SELECT * FROM `EchoPeople` WHERE username='$username'";
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
			
			foreach ($_POST['id'] as $id){
				if ($_POST['checked'][$id]) {
					$query = "DELETE FROM `$campid` WHERE camperid='$id';";
					$result = mysqli_query($connection, $query);
					if($result){
						$_SESSION['smsg'] = "Saved.";
					}else{
						$_SESSION['fmsg'] = "Save failed, " . mysqli_error($connection);
					}
					$attended = $camper['attended'];
					$attended = $attended - 1;
					$query = "UPDATE EchoPeople SET attended='$attended' WHERE username='$username'";
					$result = mysqli_query($connection, $query);
					
					$registered = $camp['registered'];
					$registered = $registered - 1;					
					$query = "UPDATE camps SET registered='$registered' WHERE campid='$campid'";
					$result = mysqli_query($connection, $query);
					
					$collected = $camp['collected'];
					$collected = $collected - $paid;
					$query = "UPDATE camps SET collected='$collected' WHERE campid='$campid'";
					$result = mysqli_query($connection, $query);	
				}
			}
		}
	}
	echo "1";
	$query = "SELECT EchoPeople.first, EchoPeople.last, EchoPeople.id, `$campid`.amtpaid\n"
    . "FROM EchoPeople, `$campid`\n"
    . "WHERE EchoPeople.id = `$campid`.camperid\n"
    . "ORDER BY `gender`, `last`, `first`";
	$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	echo "2";
	if($result->num_rows == 0){
		$_SESSION['wmsg'] = "There are no registered campers";
	}
	echo "3";
	$gquery = "SELECT EchoPeople.gender,COUNT(*) FROM EchoPeople RIGHT JOIN " . $campid . " on EchoPeople.id = " . $campid . ".camperid GROUP BY EchoPeople.gender";
	$gresult = mysqli_query($connection, $query) or die(mysqli_error($connection));
	if($gresult->num_rows == 0){
		$_SESSION['wmsg'] = "There are no registered campers";
	}
	echo "4";
	
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<h3>Camper Details</h3>
		<div class="row justify-content-center">
			<div class="col-4">
				<form class="form-signin" method="POST">
				<table>
				<?php
					while($row = $result->fetch_assoc()){
						$id = $row['id'];
						echo "<input type=\"hidden\" name=\"id[]\" value=\"$id\">";
						echo "<tr>";
						echo "<td class=\"h\">" . $row['first'] . " " . $row['last'] . "</td><td> <input type=\"text\" name=\"paid[". $id ."]\" class=\"form-control formright\" placeholder=\"Parent\" required value=\"" . $row['amtpaid'] . "\"></td><td><input type=\"checkbox\" name=\"checked[$id]\" value=\"1\"></td>\n";
						echo "</tr>";
					}
				?>
				</table>

					<input type="hidden" name="campid" value="<?php echo $campid; ?>">
					<button class="btn btn-primary btn-block btn-ered" name="save" value="save" type="submit">Save</button>							
					<button class="btn btn-primary btn-block btn-ered" name="save" value="delete" type="submit">Unregister</button>							
				</form>
				<hr>
				<form class="form-signin" method="POST" action="./edit.php">
					<button class="btn btn-primary btn-ered" name="campid" value="<?php echo $campid; ?>" type="submit">Back</button>
				</form>
			</div>
		</div>
		<hr>
	</div>
<?php
	require('footer.php');
?>