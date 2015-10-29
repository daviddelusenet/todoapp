<?php
	$db_hostname = 'YOURHOSTNAME';
	$db_username = 'YOURUSERNAME';
	$db_password = 'YOURPASSWORD';			
	$db_database = 'YOURDATABASE';
	
	$con = mysqli_connect($db_hostname,$db_username,$db_password,$db_database) or die("database error");
?>