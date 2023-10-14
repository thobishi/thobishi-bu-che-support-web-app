<?php
	// $path="../";
	require_once("_systems/nr-online.php");
	$NRonline = new NRonline(2);
	$prog_id = readGET("prog_id", 0);
	$instName = $NRonline->getInstitutionName($prog_id);
	$fileName = str_replace(" ","_",$instName) ."_ser_panel_criteria_".date('d-m-Y').".pdf";
	// $NRonline->previewReadOnly($tmpSettings, $path, $pdf = true, $fileName);
	$NRonline->displayCriteriaComparison($prog_id, $pdf = true, $fileName);
?>
