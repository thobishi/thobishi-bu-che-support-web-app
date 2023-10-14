<?php

	if (empty($_GET['id'])) die ('File not found.'); 
	$id = $_GET['id'];

	include ("makeReport.php");
	
	// HTTP headers saying that it is a file stream:
	Header("Content-type: application/octet-stream");
	// passing the name of the streaming file:
	Header("Content-Disposition: attachment; filename=$file_rtf");

	echo $file_data;
?>
