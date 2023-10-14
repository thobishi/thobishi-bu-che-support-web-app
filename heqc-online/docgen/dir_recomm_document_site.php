<?php

	require_once ('/var/www/html/common/_systems/heqc-online.php');
	octoDB::connect ();
	writeXMLhead ();
?>

<DOC
	config_file="docgen/doc_config.inc"
	title="Directorate Recommendation"
	subject=""
	author="HEQC-online system"
	manager=""
	company="Council on Higher Education"
	operator=""
	category="HEQC-online system"
	keywords="site directorate recommendation"
	comment=""
>

<?php

//set variables for document
	$site_proc_id = readGET("proc_id");
	$report = HEQConline::generateSiteRecomm($site_proc_id,"recomm");
		$report = mb_convert_encoding($report, 'UTF-8', 'UTF-8');
	echo $report;

?>
</DOC>
