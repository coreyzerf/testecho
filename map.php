<?php
	$title = "Contact";
	require('header.php');
		
	msgbox ($_SESSION['smsg'], $_SESSION['fmsg'], $_SESSION['wmsg']);
	
?>
</div>
	<div class="container medium content">
		<hr>
		<h2>MAP</h2>
		<iframe width="100%" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:EikxMDU1IEJ1c2ggUmQsIEdvZGZyZXksIE9OIEswSCAxVDAsIENhbmFkYSIxEi8KFAoSCYs72iKz7tJMEZCb1Mm7kEOJEJ8IKhQKEgn1P1jnsu7STBGQWHU_CNMDcg&key=AIzaSyBAVsPLfRPumL1DrU7wwIssr-nbsRjio68" allowfullscreen></iframe> 	
		<hr>
	</div>
<?php
	require('footer.php');
?>