<?php
	require_once ('_systems/contract/contract.php');

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
