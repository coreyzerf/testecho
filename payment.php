<?php
$title="payment";
include('header.php');
//require('connect.php');
//require('functions.php');

$today = time();

// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = false;

// Database settings. Change these for your database configuration.
$dbConfig = [
	'host' => 'localhost',
	'password' => 'XXXXXXXXXxxx!',
	'name' => 'zerfca_admin'
];

// PayPal settings. Change these to your account details and the relevant URLs
// for your site.
$paypalConfig = [
	'email' => 'echolakefinance@gmail.com',
	'return_url' => 'https://echolakecamp.ca/registered.php',
	'cancel_url' => 'https://echolakecamp.ca/register.php',
	'notify_url' => 'https://echolakecamp.ca/payment.php'
];

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

// Product being purchased.
$itemName = $_POST['item_name'];
$itemAmount = $_POST['amount'];

$custom = $_POST['custom'];

// Include Functions
require 'pfunctions.php';

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {

	// Grab the post data so that we can set up the query string for PayPal.
	// Ideally we'd use a whitelist here to check nothing is being injected into
	// our post data.
	$data = [];
	foreach ($_POST as $key => $value) {
		$data[$key] = stripslashes($value);
	}

	// Set the PayPal account.
	$data['business'] = $paypalConfig['email'];

	// Set the PayPal return addresses.
	$data['return'] = stripslashes($paypalConfig['return_url']);
	$data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
	$data['notify_url'] = stripslashes($paypalConfig['notify_url']);

	// Set the details about the product being purchased, including the amount
	// and currency so that these aren't overridden by the form data.
	$data['item_name'] = $itemName;
	$data['amount'] = $itemAmount;
	$data['currency_code'] = 'CAD';

	// Add any custom fields for the query string.
	$data['custom'] = $custom;

	// Build the query string from the data.
	$queryString = http_build_query($data);

	// Redirect to paypal IPN
	header('location:' . $paypalUrl . '?' . $queryString);
	exit();

} else {
	// Handle the PayPal response.

	// Create a connection to the database.

	// Assign posted variables to local data array.
	$item_name = $_POST['item_name'];
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	$custom = $_POST['custom'];
	$item_number = $_POST['item_number'];
	
	$querycamper = "SELECT * FROM `EchoPeople` WHERE id='$custom'";
	$resultcamper = mysqli_query($connection, $querycamper) or die(mysqli_error($connection));
	$camper = $resultcamper->fetch_assoc();
	$username = $camper['username'];
	$_SESSION['username'] = $username;
	
	if (verifyTransaction($_POST)) {
			$query = "INSERT INTO `payments` (camperid, txnid, payment_amount, payment_status, itemid, createdtime) VALUES ('$custom','$txn_id','$payment_amount','$payment_status','$item_name',now());";
			
			$result = mysqli_query($connection, $query);	
		}
	$toAdmin = "echo@echolakecamp.ca,payments@echolakecamp.ca";
	$subject = "Payment from " . $payer_email . " | Echolakecamp.ca";
	$content = "
	<html>
	<body>
	<p>Hey!</p>
	<p>You've recieved this email because " . $payer_email . " has paid for a " . $item_name ."!</p>";
	$content .= "<br />
		<p>They have paid via PayPal. The transaction details are as follows:</p>
		<p>Item: " . $item_name . "</p>
		<p>Item Number: " . $item_number . "</p>
		<p>Status: " . $payment_status . "</p>
		<p>Amount: " . $payment_amount . "</p>
		<p>Currency: " . $payment_currency . "</p>
		<p>Transaction ID: " . $txn_id . "</p>
		<p>Paid To: " . $receiver_email . "</p>
		<p>Paid From: " . $payer_email . "</p>
		<p>UserID: " . $custom . "</p>
		<br />
		";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
	$headers .= 'From: Echo Lake Staff <echo@zerf.ca>' . "\r\n";
	mail($toAdmin, $subject, $content, $headers, "-f echo@echolakecamp.ca");
	addLog("email:" . $title . ",email sent to " . $toAdmin . ". This was the message: " . $content);
	
	$campid = $item_number;
	$querycamp = "SELECT * FROM `camps` WHERE campid='$campid'";
	addLog("query:" . $title . "," . $username . "," . $querycamp);	
	$resultcamp = mysqli_query($connection, $querycamp) or die(mysqli_error($connection));
	addLog(mysqli_error($connection));
	
	$querycamper = "SELECT * FROM `EchoPeople` WHERE id='$custom'";
	addLog("query:" . $title . "," . $username . "," . $querycamper);
	$resultcamper = mysqli_query($connection, $querycamper) or die(mysqli_error($connection));	
	addLog(mysqli_error());
	
	
	if($resultcamp->num_rows == 0){
		addLog("error:payment,There were no active camps found on the payment page.");
	}
	if($resultcamper->num_rows == 0){
		addLog("error:payment,No user found.");
	}
	$camper = $resultcamper->fetch_assoc();

	$camp = $resultcamp->fetch_assoc();		
addLog("1");	
	$id = $camper['id'];
	$first = $camper['first'];
	$last = $camper['last'];
	$email = $camper['email'];
	$season = $camper['season'];
	$price = $payment_amount;
	$friend = "";
	$query = "INSERT INTO " . $campid . " (camperid,amtpaid,friend,registered) VALUES ('$id','$price','$friend',now());";
	$result = mysqli_query($connection, $query);
	addLog("query:" . $title . "," . $username . "," . $querycamp);

	if($result){
		$_SESSION['smsg'] = "Successfully Registered.";
		addLog("Registration:payments," . $username . " successfully registered.");
		$aregistered = 1;
	}else{
		$_SESSION['fmsg'] = "Registration Failed, " . mysqli_error($connection);
		addLog("Registration:payments," . $username . " registration failed.");
		$aregistered = 0;
	}
	if ($aregistered) {
		$attended = $camper['attended'] + 1;
		$query = "UPDATE EchoPeople SET attended='$attended' WHERE username='$username'";
		$result = mysqli_query($connection, $query);
		addLog("query:" . $title . "," . $username . "," . $query);
		
		$registered = $camp['registered'] + 1;
		$query = "UPDATE camps SET registered='$registered' WHERE campid='$campid'";
		$result = mysqli_query($connection, $query);
		addLog("query:" . $title . "," . $username . "," . $query);
		
		$collected = $camp['collected'] + $price;
		$query = "UPDATE camps SET collected='$collected' WHERE campid='$campid'";
		$result = mysqli_query($connection, $query);
		addLog("query:" . $title . "," . $username . "," . $query);			
		
		$toEmail = $email;
		$subject = "Registered! | Echolakecamp.ca";
		$content = "
		<html>
		<body>
		<p>Hey " . $first . " " . $last . "!</p>
		<p>You've recieved this email because you have successfully registered for " . $season . " Camp!</p>
		<p>You can expect a acceptance letter soon with more details!</p>";
		$content .= "<br />
		<p>Thanks,</p>
		<p>Echo Lake Staff</p>
		";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
		$headers .= 'From: Echo Lake Staff <echo@echolakecamp.ca>' . "\r\n";
		if(mail($toEmail, $subject, $content, $headers, "-f echo@echolakecamp.ca")) {
			$_SESSION['smsg'] .= "<br />We have sent a confirmation email";
			addLog("registered:" . $title . "," . $username . ",email sent to " . $toEmail);
		}
		
		$toAdmin = "echoregistrar@gmail.com,payments@echolakecamp.ca";
		$subject = $first . " " . $last . " has registered! | Echolakecamp.ca";
		$content = "
		<html>
		<body>
		<p>Hey!</p>
		<p>You've recieved this email because " . $first . " " . $last . " has registered! They have used PayPal for payment. Please see the payment email for more details</p>";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
		$headers .= 'From: Echo Lake Staff <echo@zerf.ca>' . "\r\n";
		mail($toAdmin, $subject, $content, $headers, "-f echo@echolakecamp.ca");
		addLog("registered:" . $username . ",email sent to " . $toAdmin);
		
		$query = "SELECT * FROM `EchoPeople` ORDER BY `last`, `first`";
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
		addLog("query:" . $title . "," . $title . "," . $username . "," . $query);
			if($result->num_rows == 0){
				$_SESSION['wmsg'] = "Something Broke.";
				addLog("error:" . $title . "," . $username . ",query returned no results.");
			}
	}
}
