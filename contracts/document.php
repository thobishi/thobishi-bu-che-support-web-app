<?php
	require_once ('_systems/contract/contract.php');

	$report = readGET('r', false);
	$parm = readGET('p', false);
	$token = readGET('token');

	octoDB::connect ();

	$docGen = new octoDocGen ($report, $parm);

	if ((!$report) || (! $docGen->checkToken($token))) {
		system_filenotfound ();
	}


	if (!$docGen->generateDoc()) {
		system_filenotfound ();
	}

?>
