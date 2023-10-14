<?php
//	require_once ('document_generator/cl_xml2driver.php');
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	octoDB::connect ();
	//writeXMLhead ();
	echo '<?php xml version="1.0" encoding ="UTF-8"?>'; 
?>

<DOC
	config_file="docgen/doc_config.inc"
	title="Accreditation Committee (AC) Meeting Document"
	subject=""
	author="HEQC-online system"
	manager=""
	company="Council on Higher Education"
	operator=""
	category="HEQC-online system"
	keywords="ac meeting"
	comment=""
>

<?php

ob_start();
ob_end_clean();

//set variables for document
	$meet_id = readGET("meet_id");

	//HEQConline::displayPopulatedApplicationFormPerCriteria($meet_id, "docgen");
	//$report = HEQConline::generateACMeetingDocument($meet_id, "docgen");

	//$report .= HEQConline::generateMeetingDocumentForSites($meet_id, "recomm");
	
	//2017-09-13: Richard - Added AC agenda type
	$report = HEQConline::generateACMeetingDocument($meet_id, "docgen", "consent");
	//$report .= HEQConline::generateMeetingDocumentForSites($meet_id, "recomm", "consent");
	
	$report .= HEQConline::generateACMeetingDocument($meet_id, "docgen", "discuss");
	$report .= HEQConline::generateMeetingDocumentForSites($meet_id, "recomm", "discuss");
	
	$report = mb_convert_encoding($report, 'UTF-8', 'UTF-8');

	echo $report;
?>
</DOC>
