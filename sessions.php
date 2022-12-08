<?php 
	include('header.php');
	foreach ($_SESSION as $key => $value) {
		echo '<p>'.$key.'</p>';
		echo '<p>'.$value.'</p>';
		foreach($value as $k => $v) {
			echo '<p>'.$k.'</p>';
			echo '<p>'.$v.'</p>';
			echo '<hr />';
		}
	}
?>