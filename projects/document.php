<?php
	require_once ('_systems/che_projects.php');

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
