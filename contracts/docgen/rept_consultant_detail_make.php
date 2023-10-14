<?php
	require_once ('document_generator/cl_xml2driver.php');
	require_once ('_systems/contract/contract.php');
	octoDB::connect ();
//	writeXMLhead ();

$xml_head = <<<XMLHEAD
<?php xml version="1.0" encoding="ISO-8859-1" ?>
<DOC
	config_file="docgen/doc_config.inc"
	title="CHE Contract Register System"
	subject="Consultant details"
	author="Octoplus Information Systems"
	company="Council on Higher Education"
	category="Contracts"
>
XMLHEAD;

	//set variables for document
	//	$c_id = readGET("c_id");

//	$sql = <<<DETAIL
//		SELECT * FROM d_consultants 
//DETAIL;
//	$rs = mysqli_query($sql);
//	while ($row = mysqli_fetch_array($rs)){
		// show consultants here - include nicely laid out template file.
//	}

	$file = 'rept_consultant_detail_template.php';
	include($file);


$xml_tail = <<<XMLTAIL
</DOC>
XMLTAIL;


	// this will be the name of our RTF file:
//	$file_rtf = "report-".sprintf("%04d", rand()).".doc";
	
	// creating class object specifying the driver type - "RTF"

	$xml_template = $xml_head . $xml_body . $xml_tail;
	
//	$xml = new nDOCGEN($xml_template,"RTF");

//	$file_data = $xml->get_result_file();
	
	echo $xml_template;
?>
