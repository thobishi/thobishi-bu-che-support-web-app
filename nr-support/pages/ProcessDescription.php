<?php
	$path="../";
	require_once("/var/www/common/_systems/nr-online.php");
	$NRonline = new NRonline(2);
	$id = readGET("id", 0);

	echo $NRonline->db->getValueFromTable("processes","processes_id",$id,"processes_comment") ;
?>
