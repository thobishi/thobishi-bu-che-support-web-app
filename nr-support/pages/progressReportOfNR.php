<?php
	$path="../";
	require_once("/var/www/common/_systems/nr-online.php");
	$NRonline = new NRonline(2);
	$prog_id = readGET("prog_id", 0);

	$NRonline->displayProgressReportOfNR($prog_id);
?>
