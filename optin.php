<?php
	require('connect.php');
	require('functions.php');
	session_start();
	if ( isset($_POST['optin'])){ 
		$email = $_POST['email'];
		$query = "INSERT `optin` (email) VALUES ('$email') ;";
        $result = mysqli_query($connection, $query);
        if($result){
            $_SESSION['smsg'] = "Saved.";
		}else{
            $_SESSION['fmsg'] = "Save failed, " . mysqli_error($connection);
		}
		header('Location: index.php');
    }else{
		header('Location: index.php');
	}
	
	
?>
