<?php
	$title = "Profile";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	
	if ( !acctverify($_SESSION['username'])){
		session_unset();
		$_SESSION['fmsg'] = "You are not logged in.";
		header('Location: login.php');
	}elseif (!$isadmin){
		header('Location: index.php');
	}elseif ( isset($_POST['save'])){ 
		$id = uniqid();
		$newusername = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
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
        $password =  filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
		$encpass = password_hash($password, PASSWORD_DEFAULT);
        $isprivate = $_POST['isprivate'];
        $isstaff = $_POST['isstaff']; 
        $isactive = 1; 
		
        if (pwverify($password)){
			//$query = "UPDATE EchoPeople SET username='$username', first='$first' , last='$last' , birthday='$birthday' , email='$email' , incharge='$incharge' , parent='$parent' , street='$street' , city='$city' , province='$province' , postal='$postal' , country='$country' , emergency='$emergency' , emergnum='$emergnum' ,healthnum='$healthnum' , allergy='$allergy' , healthconcerns='$healthconcerns' , medication='$medication' , phone='$phone' , church='$church' , isprivate='$isprivate' , isstaff='$isstaff' WHERE id='".$id."';";
			$query = "INSERT INTO `EchoPeople` (id,username,first,last,gender,password,email,created,activesent,birthday,parent,street,city,province,country,postal,emergency,emergnum,healthnum,allergy,healthconcerns,medication,phone,church,isprivate,isstaff,isactive) VALUES ('$id','$newusername','$first','$last','$gender','$encpass','$email',now(),now(),'$birthday','$parent','$street','$city','$province','$country','$postal','$emergency','$emergnum','$healthnum','$allergy','$healthconcerns','$medication','$phone','$church','$isprivate','$isstaff','$isactive');";
			$result = mysqli_query($connection, $query);
			if($result){
				$_SESSION['smsg'] = "Saved.";
			}else{
				$_SESSION['fmsg'] = "Save failed, " . mysqli_error($connection);
			}
		unset($_POST);
		}else{
			unset($_SESSION['smsg']);
			$_SESSION['fmsg'] = "Password must be at least 8 characters and contain 1 number";
		}
    }
	
	
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<h2>Profile</h2>
		<h3>
			Only use if a parent/camper cannot create their own account 
		</h3>
		<form class="form-signin" method="POST">
			<p>Username:<input type="text" name="username" class="form-control" placeholder="Username" required value="<?php echo $_POST['username'];?>"/></p>							
			<p>Email: <input type="email" name="email" class="form-control" placeholder="Email" required value="<?php echo $_POST['email'];?>"/>
			<p>Password: <input type="text" name="password" class="form-control" placeholder="Password" required value=""/>
			<p>First Name:<input type="text" name="first" class="form-control" placeholder="First Name" required value="<?php echo $_POST['first'];?>"/></p>
			<p>Last Name:<input type="text" name="last" class="form-control" placeholder="Last Name" required value="<?php echo $_POST['last'];?>"/></p>
			<p>Sex:<select class="form-control" name="gender">
					<option value="">Sex</option>
					<option value="m">Male</option>
					<option value="f">Female</option>
				</select></p>
			<p>Birthday:<input type="date" name="birthday" class="form-control" placeholder="Birthday (YYYY-MM-DD)" required value="<?php echo $_POST['birthday'];?>"/></p>
			<p>Parent: <input type="text" name="parent" class="form-control" placeholder="Parent"  value="<?php echo $_POST['parent'];?>"/></p>						
			<p>Phone: <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="<?php echo $_POST['phone'];?>"/></p>
			<p>Street: <input type="text" name="street" class="form-control" placeholder="Street" required value="<?php echo $_POST['street'];?>"/></p>
			<p>City: <input type="text" name="city" class="form-control" placeholder="City" required value="<?php echo $_POST['city'];?>"/>
			<p>Province: <input type="text" name="province" class="form-control" placeholder="Province" required value="<?php echo $_POST['province'];?>"/></p>
			<p>Postal Code: <input type="text" name="postal" class="form-control" placeholder="Postal Code" required value="<?php echo $_POST['postal'];?>"/></p>
			<p>Country: <input type="text" name="country" class="form-control" placeholder="Country" required value="<?php echo $_POST['country'];?>"/></p>
			<p>Emergency Contact: <input type="text" name="emergency" class="form-control" placeholder="Emergency Contact" value="<?php echo $_POST['emergency'];?>"/></p>
			<p>Emergency Number: <input type="text" name="emergnum" class="form-control" placeholder="Emergency Number" value="<?php echo $_POST['emergnum'];?>"/></p>
			<p>Allergies: <input type="text" name="allergy" rows="5" class="form-control" placeholder="Allergies" value="<?php echo $_POST['allergy'];?>"/></p>
			<p>Health Concerns: <input type="text" name="healthconcerns" rows="5" class="form-control" placeholder="Health Concerns" value="<?php echo $_POST['healthconcerns'];?>"/></p>
			<p>Medication: <input type="text" name="medication" class="form-control" placeholder="Medication" value="<?php echo $_POST['medication'];?>"/></p>
			<p>Church: <input type="text" name="church" class="form-control" placeholder="Church" value="<?php echo $_POST['church'];?>"/></p>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="isprivate" value="1"> Private
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="isstaff" value="1"> Staff
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="isactive" value="1" checked=1 disabled> Active
				</label>
			</div>
			<p>
			<button name="save" class="btn btn-primary btn-block btn-ered" type="submit" value="Save">Create</button>
			<hr>
			<a class="btn btn-primary btn-ered" href="camperadmin.php" onclick="return confirm('Are you sure? Changes will not be saved');">Back</a>
			</p>
		</form>
		<hr>
	</div>
<?php
	require('footer.php');
?>