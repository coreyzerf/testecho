<?php
	require('connect.php');
	
	function pwverify($password_string){
		$password_string = trim($password_string);
		if($password_string == ''){
			return "0";
		}elseif(strlen($password_string) < 8){
			return "0";
		}elseif(!(preg_match('#[0-9]#', $password_string))){
			return "0";
		}else{
			return "1";
		}
	}
	
	function acctverify($username){
		$connection = mysqli_connect('localhost', '', '!');
		if (!$connection){
			die("Database Connection Failed" . mysqli_error($connection));
		}
		$select_db = mysqli_select_db($connection, '');
		if (!$select_db){
			die("Database Selection Failed" . mysqli_error($connection));
		}
		$query = "SELECT * FROM `EchoPeople` WHERE username='$username'";		 
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
		$count = mysqli_num_rows($result);
		$row = $result->fetch_assoc();
		if($count == 1){
			if($row['isactive']){
				return "1";
			}else{
				return "2";
			}
		}
		return "0";
	}
	
	function age($bday, $today=NULL){
		$year = date("Y");
		$birthday = new DateTime($bday);
		$today = new DateTime($year . '-12-31');
		$interval = $today->diff($birthday);
		return $interval->format('%y');
	}
	
	function eligible($username, $alert){
		$connection = mysqli_connect('localhost', '', '!');
		if (!$connection){
			die("Database Connection Failed" . mysqli_error($connection));
		}
		$select_db = mysqli_select_db($connection, '');
		if (!$select_db){
			die("Database Selection Failed" . mysqli_error($connection));
		}
		
		$query = "SELECT * FROM `EchoPeople` where username='$username'";
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
		$row = $result->fetch_assoc();
		
		//Load database into variables
		$id = $row['id'];
		$username = $row['username'];
		$first = $row['first']; 
        $last = $row['last'];
        $gender = $row['gender'];
		$email = $row['email'];
        $incharge = $row['incharge'];
        $parent = $row['parent'];
        $street = $row['street'];
        $city = $row['city'];
        $province = $row['province'];
        $postal = $row['postal'];
        $country = $row['country'];
        $emergency = $row['emergency'];
		$emergnum = $row['emergnum'];
        $healthnum = $row['healthnum'];
        $healthconcerns = $row['healthconcerns'];
        $medication = $row['medication'];
        $phone = $row['phone'];
        $church = $row['church'];
        $isprivate = $row['isprivate'];
        $isstaff = $row['isstaff']; 
        $isactive = $row['isactive'];
		$birthday = $row['birthday'];
		$covid = $row['covid'];
		
		//Required variables
		$maxage = 22;
		$minage = 14;
		$eligible = 1;
		
		//Variable processing
		$age = age($birthday);
		
		//Age Check
		if( $age >= $maxage ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "You or your child are too old for camp.<br>";}
			$eligible = 0;
		}
		if( $age < $minage ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "You or your child are too young for camp.<br>";
				$_SESSION['fmsg'] .= 'If this is not in error, check out <a href="https://www.harbourridgecamp.com">www.harbourridgecamp.com</a><br>';}
			$eligible = 0;
		}
		if ( empty($first)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your first name is required.<br>";}
			$eligible = 0;
		} 
		if( empty($last)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your last name is required.<br>";}
			$eligible = 0;
		} 
		if( empty($gender)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "You need to indicate your sex.<br>";}
			$eligible = 0;
		} 
		if( empty($birthday)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your birthday is required.<br>";}
			$eligible = 0;
		} 
		if( empty($email)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your email is required, or is formatted incorrectly.<br>";}
			$eligible = 0;
		} 
		if( empty($street)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your street address is required.<br>";}
			$eligible = 0;
		} 
		if( empty($city)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your city is required.<br>";}
			$eligible = 0;
		} 
		if( empty($province)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your province is required.<br>";}
			$eligible = 0;
		} 
		if( empty($postal)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your postal code is required.<br>";}
			$eligible = 0;
		} 
		if( empty($country)  ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your country is required.<br>";}
			$eligible = 0;
		} 
		if( empty($emergency) || empty($emergnum) ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your emergency contact information is required.<br>";}
			$eligible = 0;
		} 
		if( empty($phone) ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "Your phone number is required.<br>";}
			$eligible = 0;
		}
	if( empty($covid) and $_SESSION['iscovid'] == true ) {
			if($alert){ 
				$_SESSION['fmsg'] .= "You must agree to provide proof of vaccination or a negative COVID test.<br>";}
			$eligible = 0;
		}
		
		if ( !$eligible ){$_SESSION['fmsg'] .= "<a href=./profile.php>Click here to check your profile</a>";}
		
		return $eligible;
	}
	
	function msgbox ($smsg, $fmsg, $wmsg){
		if(isset($_SESSION['smsg'])){ 
			$smsg = $_SESSION['smsg'];
			echo "<div class=\"alert alert-success\">\n";
			echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n<span aria-hidden=\"true\">&times;</span></button>";
			echo $_SESSION['smsg'] . "\n";
			echo "</div>\n\n";
			unset($_SESSION['smsg']);
		}
		if(isset($_SESSION['fmsg'])){ 
			$smsg = $_SESSION['fmsg'];
			echo "<div class=\"alert alert-danger\">\n";
			echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n<span aria-hidden=\"true\">&times;</span></button>";
			echo $_SESSION['fmsg'] . "\n";
			echo "</div>\n\n";
			unset($_SESSION['fmsg']);
		}
		if(isset($_SESSION['wmsg'])){ 
			$smsg = $_SESSION['wmsg'];
			echo "<div class=\"alert alert-warning\">\n";
			echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n<span aria-hidden=\"true\">&times;</span></button>";
			echo $_SESSION['wmsg'] . "\n";
			echo "</div>\n\n";
			unset($_SESSION['wmsg']);
		}
	}
	
	function sec_session_start() {
		$session_name = 'sec_session_id';   // Set a custom session name
		/*Sets the session name. 
		 *This must come before session_set_cookie_params due to an undocumented bug/feature in PHP. 
		 */
		session_name($session_name);
	 
		$secure = true;
		// This stops JavaScript being able to access the session id.
		$httponly = true;
		// Forces sessions to only use cookies.
		if (ini_set('session.use_only_cookies', 1) === FALSE) {
			header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
			exit();
		}
		// Gets current cookies params.
		$cookieParams = session_get_cookie_params();
		session_set_cookie_params($cookieParams["lifetime"],
			$cookieParams["path"], 
			$cookieParams["domain"], 
			$secure,
			$httponly);
	 
		session_start();            // Start the PHP session 
		session_regenerate_id(true);    // regenerated the session, delete the old one. 
	}

	function isregistered($conn, $campid, $id){
		$query = "SELECT camperid FROM `$campid` WHERE camperid='$id'";
		addLog("query:functions," . $query);
		$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
		$row = $result->fetch_assoc();
		
		if ($id == $row['camperid']) { return 1; }
		else { return 0; }
	}
	
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	function addLog($log_msg) {
    $log_filename = "logs";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0666, true);
    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    file_put_contents($log_file_data, date("Y-m-d H:i:s") . "-" . $log_msg . "\n", FILE_APPEND);
    }
	
	function displayModal($modalTitle,$modalContent,$modalSize = "") {
		echo "<div class=\"modal fade\" id=\"myModal\" data-backdrop=\"static\" role=\"dialog\">";
			echo "<div class=\"modal-dialog $modalSize modal-dialog-centered\" role=\"document\">";
			echo "<div class=\"modal-content\">";
			  echo "<div class=\"modal-header\">";
				echo "<h5 class=\"modal-title\" id=\"exampleModalLabel\">$modalTitle</h5>";
			   echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">";
				  echo "<span aria-hidden=\"true\">&times;</span>";
				echo "</button>";
			  echo "</div>";
			  echo "<div class=\"modal-body\">";
				echo "$modalContent";
			  echo "</div>";
			  echo "<div class=\"modal-footer\">";
				echo "<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>";
			  echo "</div>";
			echo "</div>";
		  echo "</div>";
		echo "</div>";
	}
	
	function usernameValidation($user) {
		if ($user == trim($user) && strpos($user, ' ') !== false) {
			return true;
		}elseif (strpos($user, 'btc') !== false) {
			return true;
		}else{
			return false;
		}
	}
?>
