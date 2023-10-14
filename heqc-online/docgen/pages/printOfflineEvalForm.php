<?php 
$run_in_script_mode = true;

$path = '../';
require_once ('/var/www/html/common/_systems/heqc-online.php');

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
	$forms = array ("evalReportForm1", "evalReportForm2", "evalReportForm3", "evalReportForm10", "evalReportForm11", "evalReportForm4", "evalReportForm5", "evalReportForm6", "evalReportForm7");

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
$str = ob_get_flush ();
$h = fopen("/tmp/offline.doc", "w+");
fwrite ($h, $str);
fclose ($h);
?> 
