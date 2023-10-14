<?php
	$path="../";
	require_once("/var/www/html/common/_systems/nr-online.php");
	$NRonline = new NRonline(2);
	$prog_id = readGET("prog_id", 0);
	$nr_type = $NRonline->db->getValueFromTable("nr_programmes","id",$prog_id,"nr_national_review_id");
	print_r($nr_type);
	$instName = $NRonline->getInstitutionName($prog_id);
	print_r($instName);
	$fileName = str_replace(" ","_",$instName) ."_online_tables_".date('d-m-Y').".pdf";
	print_r($fileName);
	$tmpSettings = "PREV_WORKFLOW=8%7C32&ACTPROC=344&CURRENT_TABLE=nr_programmes&DBINF_nr_programmes___id=".$prog_id."&DBINF____=NEW";
	$NRonline->previewReadOnly($tmpSettings, $path, $pdf = true, $fileName, $nr_type);
?>
 