<?php
	require_once ('_systems/nr-online.php');

	$file = readGET('file', false);
	$token = readGET('token');

	$dbConnect = new dbConnect();

	if ((!$file) || (!octoToken::check($file, $token))) {
		system_filenotfound ();
	}

	$doc = new octoDoc ($file, $dbConnect->conn);

	if (!$doc->downloadFile()) {
		system_filenotfound ();
	}
	
?>
