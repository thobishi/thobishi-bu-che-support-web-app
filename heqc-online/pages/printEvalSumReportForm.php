<?php 
$run_in_script_mode = true;
}
$path = '../';
require_once ('/var/www/html/common/_systems/heqc-online.php');

function doOutPutBuffer ($buffer) {
	$search_array = array("/\<script\>.*\<\/script\>/sU", "/(\<a.*[^>]href=.*\>)(.*)(\<\/a\>)/U");
	$replace_array = array("", "\\2");

	$html = $buffer;
	$html = preg_replace ($search_array, $replace_array, $buffer);

	return $html;
}

ob_start("doOutPutBuffer");

$app = new HEQConline (1);
$str = base64_decode($_GET["workflow_settings"]);
$app->parseWorkFlowString($str);
?>
<html>
<head>
<link rel=STYLESHEET TYPE="text/css" href="../styles.css" title="Normal Style">
</head>
<title><?php echo $_GET["title"]?></title>
<script>
	function doFocus() {
		//self.close();
	}
</script>
<body>
<?php 
	$forms = array ("evalReportSumScreenForm1", "evalReportSumScreenForm2", "evalReportSumScreenForm3", "evalReportSumScreenForm8", "evalReportSumScreenForm4", "evalReportSumScreenForm5", "evalReportSumScreenForm6", "evalReportSumScreenForm7");

	foreach ($forms as $form) {
		$run_in_script_mode = true;
		$app = new HEQConline (1);
		$app->parseWorkFlowString($str);
		$app->template = $form;
		$app->view = 1;
		$app->formStatus = FLD_STATUS_DISABLED;
		$app->readTemplate();
		$app->createHTML($app->body);

		unset ($app);
	}
?>	
</body></html>
<?php 
ob_end_flush();
?> 
