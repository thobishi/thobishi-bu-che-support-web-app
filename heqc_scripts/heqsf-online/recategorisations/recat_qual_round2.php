<?php
	include_once('database.php');
	$conn = connect();
	set_time_limit(0);
	recat_qual($conn,'x_recat2_201403');
?>