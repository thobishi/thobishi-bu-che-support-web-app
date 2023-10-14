<?php

//	require_once ('document_generator/cl_xml2driver.php');
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	octoDB::connect ();
	//writeXMLhead ();
		echo '<?php xml version="1.0" encoding="UTF-8" ?>'; 


		ob_start();
		ob_end_clean();
?>

<DOC
	config_file="docgen/doc_config.inc"
	title="HEQC Meeting Minutes"
	subject=""
	author="HEQC-online system"
	manager=""
	company="Council on Higher Education"
	operator=""
	category="HEQC-online system"
	keywords="heqc meeting minutes"
	comment=""
	
>

<?php

//set variables for document
	$heqc_meet_id = readGET("heqc_meet_id");

	$report = HEQConline::generateHEQCMeetingMinutes($heqc_meet_id);
	$report = mb_convert_encoding($report, 'UTF-8', 'UTF-8');
	echo $report;

?>
</DOC>
