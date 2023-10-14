<?php

	require_once ('/var/www/html/common/_systems/heqc-online.php');
	//octoDB::connect ();
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
	keywords="directorate recommendation"
	comment=""
>

<?php

//set variables for document
	$proc_id = readGET("proc_id");
$report = HEQConline::generateRecomm($proc_id);
		$report = mb_convert_encoding($report, 'UTF-8', 'UTF-8');
	//	file_put_contents('php://stderr', print_r("URL : ".$report, TRUE));
	echo $report;

?>
</DOC>
