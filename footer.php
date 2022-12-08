	<!--PHP FOOTER BEGIN-->
<div class="container medium white footer">
	<div class="row">
		<div class="col-md-4">
			<h3>The Re-Echo</h3>
			<p>
				Usually comes out once a year. Get updates on whats happening at Camp, and how you can help!
			</p>
			<form class="form-signin" action="./optin.php" method="post">
				<div class="form-row">
					<div class="col-8">
						<input type="email" name="email" class="form-control" placeholder="Email" required> 
					</div>
				</div>
				<input class="btn btn-primary btn-ered" name="optin" value="OK" type="submit">					
			</form>
		</div>
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
			<h4>FOLLOW US:</h4>
			<a href="https://www.facebook.com/EchoLakeCamp/" class="whitelink"><img src="images/fbwht.png" class="facebook" alt="logo">Facebook</a><br> 
			<a href="https://twitter.com/EchoLakeCamp/" class="whitelink"><img src="images/twitwht.png" class="twitter" alt="logo">Twitter</a><br> 
			<a href="https://www.instagram.com/echolakecamp/" class="whitelink"><img src="images/instawht.png" class="google" alt="logo">Instagram</a><br> 
			<a href="https://www.youtube.com/echolakecamp/" class="whitelink"><img src="images/ytsqwht.png" class="youtube" alt="logo">Youtube</a>
		</div>
	</div>
	<div align="right">
		<p>
			Echo Lake Youth Ministries | 1956 - <?php echo date("Y"); ?> | All Rights Reserved | <a href="./privacy.php" class="whitelink">Privacy Policy</a>
		</p>
	</div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#myModal").modal('show');
    });
</script>
</body>
</html>