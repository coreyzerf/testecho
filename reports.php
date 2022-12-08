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
		<h2>Reports</h2>
			<?php
				while($row = $result->fetch_assoc()){
					$campid = $row['campid'];
					$season = $row['season'];
					echo "<table class=\"reports\">";
					echo '<thead><tr>';
					echo "<td class=\"c\">Season: " . $row['season'] . " Date: " . $row['date'] . "</td>";
					echo '</tr></thead>';
					echo '</table>';
					echo "<table class=\"reports\">";
					echo '<tr>';
					echo '<form class="form-signin" method="POST" action="./reports/registrar.php">';
					echo "<td class='c'><button name=\"get\" class=\"btn btn-primary btn-ered\" value=\"" . $campid ."\" type=\"submit\">Registrar</button></td></form>";
					echo '<form class="form-signin" method="POST" action="./reports/medical.php">';
					echo "<td class='c'><button name=\"get\" class=\"btn btn-primary btn-ered\" value=\"" . $campid ."\" type=\"submit\">Medical</button></td></form>";
					echo '<form class="form-signin" method="POST" action="./reports/kitchen.php">';
					echo "<td class='c'><button name=\"get\" class=\"btn btn-primary btn-ered\" value=\"" . $campid ."\" type=\"submit\">Kitchen</button></td></form>";
					echo '</tr>';										
					echo '</table>';										
				}
			?>
			<table class="reports">
				<thead><tr>
					<td class="c">Others</td>
				</tr></thead>
				<tr>
					<form class="form-signin" method="POST" action="./reports/mail.php">
					<td class='c'><button name="get" class="btn btn-primary btn-ered" value="" type="submit">Eligible Camper Mail Merge</button></td></form>
				</tr>
				<tr>
					<form class="form-signin" method="POST" action="./reports/optin.php">
					<td class='c'><button name="get" class="btn btn-primary btn-ered" value="" type="submit">Email List</button></td></form>
				</tr>
				<tr>
					<form class="form-signin" method="POST" action="./reports/emails.php">
					<td class='c'><button name="get" class="btn btn-primary btn-ered" value="" type="submit">Camper Email List</button></td></form>
				</tr>
				
			</table>
			<hr>
			<a class="btn btn-primary btn-ered" href="admin.php">Back</a>
		</form>
		<hr>
	</div>
<?php
	require('footer.php');
?>