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
		addLog("ISSUE:camperprofile.php," . $id . "," . $username . " tried to access a camper profile!");
	}elseif ( isset($_POST['save'])){ 
		$id = $_POST['id'];
		$camper = $_POST['username'];
		$_SESSION['camper'] = $id;
		$first = filter_input(INPUT_POST, 'first', FILTER_SANITIZE_STRING);
        $last = filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING);
		$gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
        $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $incharge = filter_input(INPUT_POST, 'incharge', FILTER_SANITIZE_STRING);
        $parent = filter_input(INPUT_POST, 'parent', FILTER_SANITIZE_STRING);
        $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_STRING);
        $postal = filter_input(INPUT_POST, 'postal', FILTER_SANITIZE_STRING);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
        $emergency = filter_input(INPUT_POST, 'emergency', FILTER_SANITIZE_STRING);
        $emergnum = filter_input(INPUT_POST, 'emergnum', FILTER_SANITIZE_STRING);
        $healthnum = 0;
        $allergy = $_POST['allergy']; filter_input(INPUT_POST, 'allergy', FILTER_SANITIZE_STRING);
        $healthconcerns = filter_input(INPUT_POST, 'healthconcerns', FILTER_SANITIZE_STRING);
        $medication = filter_input(INPUT_POST, 'medication', FILTER_SANITIZE_STRING);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
        $church =  filter_input(INPUT_POST, 'church', FILTER_SANITIZE_STRING);
        $isprivate = $_POST['isprivate'];
        $isstaff = $_POST['isstaff']; 
        $isactive = $_POST['isactive']; 
		
        //$query = "UPDATE EchoPeople SET username=$camper , first=$first , last=$last , birthday=$birthday , email=$email , incharge='$incharge' , parent=$parent , street=$street , city=$city , province=$province , postal=$postal , country=$country , emergency=$emergency , healthnum=$healthnum , healthconcerns=$healthconcerns , medication=$medication , phone=$phone , church=$church , isprivate='$isprivate' , isstaff='$isstaff' , isactive='$isactive' WHERE username='".$camper."';";
		$query = "UPDATE EchoPeople SET username='$camper', first='$first' , last='$last' , gender='$gender' , birthday='$birthday' , email='$email' , incharge='$incharge' , parent='$parent' , street='$street' , city='$city' , province='$province' , postal='$postal' , country='$country' , emergency='$emergency' , emergnum='$emergnum' , healthnum='$healthnum' , allergy='$allergy', healthconcerns='$healthconcerns' , medication='$medication' , phone='$phone' , church='$church' , isprivate='$isprivate' , isstaff='$isstaff' WHERE id='".$id."';";
        $result = mysqli_query($connection, $query);
        if($result){
            $_SESSION['smsg'] = "Saved.";
			addLog("access:camperprofile.php," . $username . " modified " . $first . " " . $last . "(" . $delete . ") s profile!");
		}else{
            $_SESSION['fmsg'] = "Save failed, " . $result;
		}
    }elseif ( isset($_POST['delete'])){
		$delete = $_POST['delete'];
		$_SESSION['camper'] = $delete;
		unset($_POST);
		
		$query = "SELECT * FROM `EchoPeople` WHERE id='$delete'";
	 	$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
		$row = $result->fetch_assoc();
		$staff = $row['isstaff'];
		$attended = $row['attended'];
		addLog("access:camperprofile.php," . $username . " is attempting to delete " . $delete . " s profile!");
		if ($staff){
			$_SESSION['fmsg'] = "This profile cannot be deleted.";
		}elseif ($attended >=1){
			$_SESSION['fmsg'] = "Policy prohibits camper profiles to be deleted if campers have attended at least 1 camp.";
		}else{
			$query = "DELETE FROM EchoPeople WHERE id = '" . $delete . "';";
			$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
			if($result){
				$_SESSION['smsg'] = "Camper Deleted Successfully.";
				addLog("access:camperprofile.php," . $username . " deleted " . $delete . " s profile!");
				header('Location: camperedit.php');
			}else{
				$_SESSION['fmsg'] ="Deletion Failed " . mysqli_error($connection);
			}
		}
	} elseif (isset($_POST['input'])){
		$input = $_POST['input'];
		$input = filter_input(INPUT_POST, 'input', FILTER_SANITIZE_STRING);
		$querycamper = "SELECT * FROM `EchoPeople` WHERE username='$input'";
		$resultcamper = mysqli_query($connection, $querycamper) or die(mysqli_error($connection));
		
		if($resultcamper->num_rows != 0){
			$rowcamper = $resultcamper->fetch_assoc();
			$uniq = generateRandomString();
			$id = $rowcamper['id'];
			$first = $rowcamper['first'];
			$last = $rowcamper['last'];
			$email = $rowcamper['email'];
			
			$query = "INSERT INTO `passReset` (id,uniq) VALUES ('$id','$uniq');";
			$result = mysqli_query($connection, $query);
			
			$actual_link = "https://$_SERVER[HTTP_HOST]/"."forgot.php?id=" . $uniq;
			$toEmail = $email;
			$subject = "Password Reset | Echolakecamp.ca";
			$content = "
			<html>
			<body>
			<p>Hey " . $first . " " . $last . "!</p>
			<p>Please click this link to reset your password!.</p>
			<p>" . $actual_link . "</p>
			<p>If you didn't request this, you can safely ignore.</p>
			<p>Thanks,</p>
			<p>Echo Lake Staff</p>
			";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
			$headers .= 'From: Echo Lake Staff <admin@zerf.ca>' . "\r\n";
			if(mail($toEmail, $subject, $content, $headers, "-f admin@zerf.ca")) {
				$_SESSION['wmsg'] = "We have sent " . $first . " " . $last . " an email.";	
				addLog("forgot:camperprofile.php," . $id . "," . $input . " had a password reset sent by " . $username . ". Email sent to " . $toEmail);
			}
		}
	}
	if (isset($_POST['camper'])){
		$camper = $_POST['camper'];
		addLog("access:camperprofile.php," . $username . " accessed " . $camper . " s profile!");
	}else{
		$camper = $_SESSION['camper'];
		addLog("access:camperprofile.php," . $username . " accessed " . $camper . " s profile!");
	}
	$query = "SELECT * FROM `EchoPeople` WHERE id='$camper'";
	 
	$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	$row = $result->fetch_assoc();
	/*if (!isset($row['username'])){
		$_SESSION['fmsg'] = "Something went wrong";
		header('Location: camperedit.php');
	}*/
	$first = $row['first'];
	$last = $row['last'];
	
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<h2>Editing <?php echo $first . " " . $last; ?></h2>
		<form class="form-signin" method="POST">
			<p>ID:<input type="text" name="id" class="form-control" readonly value="<?php echo $row['id'];?>"/></p>
			<p>Created:<input type="text" name="created" class="form-control"  disabled value="<?php echo $row['created'];?>"/></p>
			<p>Username:<input type="text" name="username" class="form-control" placeholder="Username"  value="<?php echo $row['username'];?>"/></p>
			<p>Camper's First Name:<input type="text" name="first" class="form-control" placeholder="First Name"  value="<?php echo $row['first'];?>"/></p>
			<p>Camper's Last Name:<input type="text" name="last" class="form-control" placeholder="Last Name"  value="<?php echo $row['last'];?>"/></p>
			<p>Sex:<select class="form-control" name="gender">
					<option value="">Sex</option>
					<option value="m" <?php if ( $row['gender'] == "m" ) { echo "selected=\"selected\""; }?>>Male</option>
					<option value="f" <?php if ( $row['gender'] == "f" ) { echo "selected=\"selected\""; }?>>Female</option>
				</select></p>
			<p>Birthday:<input type="date" name="birthday" class="form-control" placeholder="Birthday (YYYY-MM-DD)"  value="<?php echo $row['birthday'];?>"/></p>
			<p>Email: <input type="email" name="email" class="form-control" placeholder="Email"  value="<?php echo $row['email'];?>"/>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="incharge" value="1" <?php if ( $row['incharge'] == 1){echo "checked=\"checked\"";}?>> Parent of Camper
				</label>
			</div>
			<p>Parent's Name: <input type="text" name="parent" class="form-control" placeholder="Parent"  value="<?php echo $row['parent'];?>"/></p>							
			<p>Phone: <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="<?php echo $row['phone'];?>"/></p>
			<p>Street: <input type="text" name="street" class="form-control" placeholder="Street"  value="<?php echo $row['street'];?>"/></p>
			<p>City: <input type="text" name="city" class="form-control" placeholder="City"  value="<?php echo $row['city'];?>"/>
			<p>Province: <input type="text" name="province" class="form-control" placeholder="Province"  value="<?php echo $row['province'];?>"/></p>
			<p>Postal Code: <input type="text" name="postal" class="form-control" placeholder="Postal Code"  value="<?php echo $row['postal'];?>"/></p>
			<p>Country: <input type="text" name="country" class="form-control" placeholder="Country"  value="<?php echo $row['country'];?>"/></p>
			<p>Emergency Contact: <input type="text" name="emergency" class="form-control" placeholder="Emergency Contact" value="<?php echo $row['emergency'];?>"/></p>
			<p>Emergency Phone: <input type="text" name="emergnum" class="form-control" placeholder="Emergency Phone" value="<?php echo $row['emergnum'];?>"/></p>
			<p>Allergies: <input type="text" name="allergy" rows="5" class="form-control" placeholder="Allergies" value="<?php echo $row['allergy'];?>"/></p>
			<p>Other Health Concerns: <input type="text" name="healthconcerns" rows="5" class="form-control" placeholder="Health Concerns" value="<?php echo $row['healthconcerns'];?>"/></p>
			<p>Medication: <input type="text" name="medication" class="form-control" placeholder="Medication" value="<?php echo $row['medication'];?>"/></p>
			<p>Church: <input type="text" name="church" class="form-control" placeholder="Church" value="<?php echo $row['church'];?>"/></p>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="isprivate" value="1" <?php if ( $row['isprivate'] == 1){echo "checked=\"checked\"";}?>> Private
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="isstaff" value="1"  <?php if ( $row['isstaff'] == 1){echo "checked=\"checked\"";}?>> Staff
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="isactive" value="1" disabled <?php if ( $row['isactive'] == 1){echo "checked=\"checked\"";}?>> Active
				</label>
			</div>
			<p>Camps Attended: <input type="text" name="attended" class="form-control" placeholder="Attended" value="<?php echo $row['attended'];?>" disabled /></p>
			<p><button name="save" class="btn btn-primary btn-block btn-ered" type="submit" value="Save">Save Profile</button>
			<button name="delete" class="btn btn-danger btn-block" type="submit" value="<?php echo $row['id'];?>" onclick="return confirm('Are you sure you want to delete this item?');" formnovalidate>Delete Profile</button>
			
		</form>
		<form class="form-signin" method="POST">
			<input type="hidden" name="input" class="form-control" placeholder="Username" value="<?php echo $row['username'];?>">
			<button class="btn btn-primary btn-block btn-ered" type="submit">Send Password Reset</button></p>
		</form>
		<form class="form-signin" method="POST" action="./manualregister.php">
			<button class="btn btn-primary btn-block btn-ered" name="manual" type="submit" value="<?php echo $row['id'];?>">Register <?php echo $row['first'];?> for Camp</button>
		</form>
		<hr>
		<a class="btn btn-primary btn-ered" href="camperedit.php">Back</a>
		<hr>
	</div>
<?php
	require('footer.php');
?>