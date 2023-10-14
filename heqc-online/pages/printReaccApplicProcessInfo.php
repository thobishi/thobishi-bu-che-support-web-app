<?php 
$run_in_script_mode = true;
$path = '../';
require_once ('/var/www/html/common/_systems/heqc-online.php');

function doOutPutBuffer ($buffer) {
	$h = fopen ("/tmp/che_mis_output.html", "w+");
	$search_array = array("/\<script\>.*\<\/script\>/sU", "/(\<a.*[^>]href=.*(?:openFileWin|changeCMD|winContentText.*).*\>)(.*)(\<\/a\>)/U");
	$replace_array = array("", "\\2");

	$html = $buffer;
	$html = preg_replace ($search_array, $replace_array, $buffer);

	fwrite($h, $html);

	return $html;
}

ob_start("doOutPutBuffer");

$app = new HEQConline (1);
$str = base64_decode($_GET["workflow_settings"]);
$workflow_arr = explode("&", $str);

foreach ($workflow_arr as $item) {
	$pair = explode ("=", $item);
	if ($pair[0] == "DBINF_HEInstitution___HEI_id") {
		$inst_id = $pair[1];
	}
	if ($pair[0] == "DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id") {
//		2008-09-13 Robin: No user_ref on reaccreditation table - getting administrator rather
//		$current_user_id = $app->getValueFromTable("Institutions_application", "application_id", $pair[1], "user_ref");
		$application_id = $pair[1];
	}
}
$current_user_id = $app->getInstitutionAdministrator(0,$inst_id);


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
<table width="98%">
<tr><td align="right">
	<?php echo '<a align="right" href="javascript:window.print();">Print</a>'; ?>
	</td>
</tr></table>
<?php 
	// Robin: 20 July 2012

	$app->displayReaccApplicProcessInfo($application_id, $str);

?>
</body></html>

<?php 
ob_end_flush();
?>
