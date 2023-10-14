<?php 
$path = '../';
require_once ('/var/www/html/common/_systems/heqc-online.php');

$run_in_script_mode = true;

function doOutPutBuffer ($buffer) {
	$buffer = (str_replace("<script>", "<!-- <script>", $buffer));
	$buffer = (str_replace("</script>", "</script> -->", $buffer));
	return $buffer;
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
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td height="2"></td>
</tr>
</table>
<?php 
	$forms = array ("evalReportViewForm1", "evalReportViewForm2", "evalReportViewForm3", "evalReportViewForm8", "evalReportViewForm9", "evalReportViewForm4", "evalReportViewForm5", "evalReportViewForm6", "evalReportViewForm7");

	foreach ($forms as $form) {
		$run_in_script_mode = true;
		$app = new HEQConline (1);
		$app->parseWorkFlowString($str);
		$app->template = $form;
		$app->view = 1;
		$app->formStatus = FLD_STATUS_TEXT;
		$app->readTemplate();
		$app->createHTML($app->body);
		
		unset ($app);
	}
?>	
</body></html>

<?php 
ob_end_flush();
?> 
