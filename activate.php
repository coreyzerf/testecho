<?php
	$title = "Activate";
	//include('header.php');
	//require('connect.php');
	require('functions.php');
	session_start();

	session_destroy();
	session_start();
	
	$id = $_GET['id'];
	if(!empty($id)) {
		$query = "SELECT * FROM `EchoPeople` WHERE id='$id'";
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
		addLog("query:" . $title . "," . $id . "," . $query);
		$row = $result->fetch_assoc();
		if(!$result){
			$_SESSION['fmsg'] = "Problem in account activation.";
			addLog("error:" . $title . ",no result in EchoPeople for id" . $id );
			header('Location: login.php');
		}elseif ($row['isactive'] == 1){
			$_SESSION['fmsg'] = "Activation link was already used.";
			addLog("error:" . $title . ",Activation link alread used for id" . $id );
			header('Location: login.php');
		}else{
			$isactive = 1;
			$query = "UPDATE EchoPeople SET isactive='$isactive' WHERE id='".$id."';";
			$result = mysqli_query($connection, $query);
			addLog("query:" . $title . "," . $id . "," . $query);
			if($result) {
				$_SESSION['smsg'] = "Your account is activated.";
				addLog("activation:" . $title . ",account activated for id" . $id );
			} else {
				$_SESSION['fmsg'] = "Problem in account activation.";
				addLog("error:" . $title . ",no result in EchoPeople for id" . $id );
			}
			header('Location: login.php');
		}
	}
?>