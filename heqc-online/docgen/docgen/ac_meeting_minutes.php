<?php
//	require_once ('document_generator/cl_xml2driver.php');
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	octoDB::connect ();
	//writeXMLhead ();
	echo '<?php xml version="1.0" encoding="UTF-8" ?>'; 
?>

<DOC
	config_file="docgen/doc_config.inc"
	title="Accreditation Committee (AC) Meeting Minutes"
	subject=""
	author="HEQC-online system"
	manager=""
	company="Council on Higher Education"
	operator=""
	category="HEQC-online system"
	keywords="ac meeting minutes"
	comment=""
>

<?php

//set variables for document
	$meet_id = readGET("meet_id");

	//HEQConline::displayPopulatedApplicationFormPerCriteria($meet_id, "docgen");
	$report = HEQConline::generateACMeetingMinutes($meet_id);
	$report = mb_convert_encoding($report, 'UTF-8', 'UTF-8');
	echo $report;

?>
</DOC>
