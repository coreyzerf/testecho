<?php
	$title = "Registration";
	include('header.php');
	//require('connect.php');
	//require('functions.php');
	
	$today = time();
	
	
	
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="content">
		<div class="register">
			<div>
				<div>
					<div class="register">
						<h3>Register for <?php echo $camp['season'];?> Camp <?php echo date('Y', strtotime($camp['date']));?></h3>
						
						<form class="paypal" action="payment.php" method="post" id="paypal_form">
							<input type="hidden" name="cmd" value="_xclick" />
							<input type="hidden" name="no_note" value="1" />
							<input type="hidden" name="lc" value="UK" />
							<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
							<input type="hidden" name="first_name" value="Customer's First Name" />
							<input type="hidden" name="last_name" value="Customer's Last Name" />
							<input type="hidden" name="payer_email" value="customer@example.com" />
							<input type="hidden" name="item_number" value="123456" / >
							<input type="submit" name="submit" value="Submit Payment"/>
						</form>
						
						<a class="button buttonwide button-top-tiny" href="index.php">Home</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	require('footer.php');
?> 