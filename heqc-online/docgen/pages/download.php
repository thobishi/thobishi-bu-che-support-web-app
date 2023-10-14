<?php
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	require_once ('var/www/html/common/workflow-1.0/class.octoDB.php');
	require_once ('var/www/html/common/workflow-1.0/class.octoToken.php');

	$file = readGET('file', false);
	$token = readGET('token');

	octoDB::connect ();	

	if ((!$file) || (!octoToken::check($file, $token))) {
		system_filenotfound ();
	}

	$doc = new octoDoc ($file);

	if (!$doc->downloadFile()) {
		system_filenotfound ();
	}
	
?>
