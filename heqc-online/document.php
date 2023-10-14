<?php
        require_once ('/var/www/html/common/workflow-1.0/class.octoDocGen.php');
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	require_once ('/var/www/html/common/document_generator/cl_xml2driver.php');

	$report = readGET('r', false);
	$parm = readGET('p', false);
	$token = readGET('token');

	file_put_contents('php://stderr', print_r("report: ".$report.", parm: ".$parm.", token: ".$token, TRUE));

	octoDB::connect ();

	$docGen = new octoDocGen ($report, $parm);

	if ((!$report) || (! $docGen->checkToken($token))) {
//		system_filenotfound ();
	}


	if (!$docGen->generateDoc()) {
		system_filenotfound ();
	}

?>
