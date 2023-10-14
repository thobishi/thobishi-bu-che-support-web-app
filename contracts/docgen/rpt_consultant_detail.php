<?php
	require_once ('document_generator/cl_xml2driver.php');
	require_once ('_systems/contract/contract.php');
	octoDB::connect ();
	writeXMLhead ();
?>

<DOC
	config_file="docgen/doc_config.inc"
	title="CHE Contract Register System"
	subject="Consultant details"
	author="Octoplus Information Systems"
	company="Council on Higher Education"
	category="Contracts"
>

<?php

	//set variables for document
	//	$c_id = readGET("c_id");

	$sql = <<<DETAIL
		SELECT * FROM d_consultants 
DETAIL;
	$rs = mysqli_query($sql);
	while ($row = mysqli_fetch_array($rs)){
		// show consultants here - include nicely laid out template file.
	}

?>

</DOC>
