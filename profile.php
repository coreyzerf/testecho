<?php
	$title = "Profile";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	
	if ( !acctverify($_SESSION['username'])){
		session_unset();
		$_SESSION['fmsg'] = "You are not logged in.";
		header('Location: login.php');
	}elseif ( isset($_POST['save'])){ 
		$id = $_SESSION['id'];
		$username = $_SESSION['username'];
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
		$covid = $_POST['covid'];
		
        //$query = "UPDATE EchoPeople SET username=$username , first=$first , last=$last , birthday=$birthday , email=$email , incharge='$incharge' , parent=$parent , street=$street , city=$city , province=$province , postal=$postal , country=$country , emergency=$emergency , healthnum=$healthnum , healthconcerns=$healthconcerns , medication=$medication , phone=$phone , church=$church , isprivate='$isprivate' , isstaff='$isstaff' , isactive='$isactive' WHERE username='".$username."';";
		$query = "UPDATE EchoPeople SET username='$username', first='$first' , last='$last' , gender='$gender' , birthday='$birthday' , email='$email' , incharge='$incharge' , parent='$parent' , street='$street' , city='$city' , province='$province' , postal='$postal' , country='$country' , emergency='$emergency' , emergnum='$emergnum' ,healthnum='$healthnum' , allergy='$allergy' , healthconcerns='$healthconcerns' , medication='$medication' , phone='$phone' , church='$church' , isprivate='$isprivate' , isstaff='$isstaff', covid='$covid' WHERE id='".$id."';";
        $result = mysqli_query($connection, $query);
		addLog("query:" . $title . "," . $username . "," . $query);
        if($result){
            $_SESSION['smsg'] = "Saved.";
		}else{
            $_SESSION['fmsg'] = "Save failed, " . mysqli_error($connection);
			$err = mysqli_error($connection);
			addLog("error:" . $title . "," . $err);
		}
    }
	$username = $_SESSION['username'];
	$query = "SELECT * FROM `EchoPeople` WHERE username='$username'";
	 
	$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	addLog("query:" . $title . "," . $username . "," . $query);
	$row = $result->fetch_assoc();
	if (!isset($row['id'])){
		session_unset();
		$_SESSION['fmsg'] = "Something went wrong";
		addLog("error:" . $title . ",No userid found.");
		header('Location: index.php');
	}
	
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<h2>Profile</h2>
		<form class="form-signin" method="POST">
			<p hidden>ID:<input type="text" name="id" class="form-control" readonly value="<?php echo $row['id'];?>"/></p>
			<hr />
			<p>COVID-19: Due to the COVID-19 pandemic, we will be requiring campers and staff to either 1) Be fully vaccinated 14 days before the start of camp, or 2) Provide proof of a negative COVID test taken within 72 hours of the start of camp. Please check the box below if you agree to provide proof for either of the conditions listed to the Camp Nurse.
			<div class="checkbox">
				<label>
					<input type="checkbox" name="covid" value="1" <?php if ( $row['covid'] == 1){echo "checked=\"checked\"";}?>> I agree
				</label>
			</div>
			<hr />
			<p>Created:<input type="text" name="created" class="form-control"  disabled value="<?php echo $row['created'];?>"/></p>
			<p>Username:<input type="text" name="username" class="form-control" placeholder="Username" required value="<?php echo $row['username'];?>"/></p>
			<p>Camper's First Name:<input type="text" name="first" class="form-control" placeholder="Camper's First Name" required value="<?php echo $row['first'];?>"/></p>
			<p>Camper's Last Name:<input type="text" name="last" class="form-control" placeholder="Camper's Last Name" required value="<?php echo $row['last'];?>"/></p>
			<p>Sex:<select class="form-control" name="gender">
					<option value="">Sex</option>
					<option value="m" <?php if ( $row['gender'] == "m" ) { echo "selected=\"selected\""; }?>>Male</option>
					<option value="f" <?php if ( $row['gender'] == "f" ) { echo "selected=\"selected\""; }?>>Female</option>
				</select></p>
			<p>Birthday (YYYY-MM-DD):<input type="date" name="birthday" class="form-control" placeholder="Birthday (YYYY-MM-DD)" required value="<?php echo $row['birthday'];?>"/></p>
			<p>Email: <input type="email" name="email" class="form-control" placeholder="Email" required value="<?php echo $row['email'];?>"/>
			<!--<div class="checkbox">
				<label>
					<input type="checkbox" name="incharge" value="1" <?php if ( $row['incharge'] == 1){echo "checked=\"checked\"";}?>> Parent of Camper
				</label>
			</div>-->
			<p>Parent's Name: <input type="text" name="parent" class="form-control" placeholder="Parent's Name" value="<?php echo $row['parent'];?>"/></p>
			<p>Phone: <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="<?php echo $row['phone'];?>"/></p>
			<p>Street: <input type="text" name="street" class="form-control" placeholder="Street" required value="<?php echo $row['street'];?>"/></p>
			<p>City: <input type="text" name="city" class="form-control" placeholder="City" required value="<?php echo $row['city'];?>"/>
			<p>Province: <input type="text" name="province" class="form-control" placeholder="Province" required value="<?php echo $row['province'];?>"/></p>
			<p>Postal Code: <input type="text" name="postal" class="form-control" placeholder="Postal Code" required value="<?php echo $row['postal'];?>"/></p>
			<p>Country: <input type="text" name="country" class="form-control" placeholder="Country" required value="<?php echo $row['country'];?>"/></p>
			<p>Emergency Contact: <input type="text" name="emergency" class="form-control" placeholder="Emergency Contact" <?php if ( $row['isstaff'] == 0 ) {echo "required";} ?> value="<?php echo $row['emergency'];?>"/></p>
			<p>Emergency Number: <input type="text" name="emergnum" class="form-control" placeholder="Emergency Number" <?php if ( $row['isstaff'] == 0 ) {echo "required";} ?> value="<?php echo $row['emergnum'];?>"/></p>
			<p>Allergies: <input type="text" name="allergy" rows="5" class="form-control" placeholder="Allergies" value="<?php echo $row['allergy'];?>"/></p>
			<p>Health Concerns: <input type="text" name="healthconcerns" rows="5" class="form-control" placeholder="Health Concerns" value="<?php echo $row['healthconcerns'];?>"/></p>
			<p>Medication: <input type="text" name="medication" class="form-control" placeholder="Medication" value="<?php echo $row['medication'];?>"/></p>
			<p>Church: <input type="text" name="church" class="form-control" placeholder="Church" value="<?php echo $row['church'];?>"/></p>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="isprivate" value="1" <?php if ( $row['isprivate'] == 1){echo "checked=\"checked\"";} if ( $row['isstaff'] == 0 ) {echo "disabled";} ?>> Private
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="isstaff" value="1"  <?php if ( $row['isstaff'] == 1){echo "checked=\"checked\"";} if ( $row['isstaff'] == 0 ) {echo "disabled";} ?>> Staff
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="isactive" value="1" disabled <?php if ( $row['isactive'] == 1){echo "checked=\"checked\"";}?>> Active
				</label>
			</div>
			<p><button name="save" class="btn btn-primary btn-block btn-ered" type="submit" value="Save">Save</button></p>
		</form>
		<a class="btn btn-primary btn-block btn-ered" href="./change.php">Change Password</a>
		<hr>
		<a class="btn btn-primary btn-ered" href="index.php">Back</a>
		<hr>
	</div>
<?php
	require('footer.php');
?>