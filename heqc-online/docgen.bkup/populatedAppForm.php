<?php
	require_once ('/var/www/html/common/document_generator/cl_xml2driver.php');
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	octoDB::connect ();
	writeXMLhead ();
?>

<DOC
	config_file="docgen/doc_config.inc"
	title="Populated Application Forms"
	subject=""
	author="HEQC-online system"
	manager=""
	company="Council on Higher Education"
	operator=""
	category="HEQC-online system"
	keywords="project budget report"
	comment=""
>

<?php

//set variables for document
	$app_id = readGET("app_id");
	$CHE_ref = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "CHE_reference_code");
	$prog_name = DBConnect::getValueFromTable("Institutions_application", "application_id", $app_id, "program_name");

	$displaySub = "<font size='20' color='#000000' align='center'><br />".$CHE_ref."<br />".$prog_name."</font>";

//	HEQConline::displayReportCoverPage("Accreditation Application Form".$displaySub);
//	HEQConline::displayGeneralPageSetup("Application Form", "landscape");
	HEQConline::displayPopulatedApplicationFormPerCriteria($app_id, "docgen");

?>

</DOC>
