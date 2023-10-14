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
	if ($pair[0] == "DBINF_Institutions_application___application_id") {
		$current_user_id = $app->getValueFromTable("Institutions_application", "application_id", $pair[1], "user_ref");
		$application_id = $pair[1];

	}
}

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

<?php	$app->getApplicationInfoTableTopForHEI_sites($application_id); ?>

<table width="98%">
<tr><td align="right">
	<?php echo '<a align="right" href="javascript:window.print();">Print</a>'; ?>
	</td>
</tr></table>
<?php 
	// Robin 27 Feb 2008
	// Application Form must print using v1 or v2 application form format.
	//$prov_type = $app->checkAppPrivPubl($application_id);
	//$app_version = $app->getValueFromTable("Institutions_application","application_id",$application_id,"app_version");
	//$hei_id = $app->getValueFromTable("Institutions_application","application_id",$application_id,"institution_id");

	$app->displayApplicationFormOverview($application_id, $str)


?>
</body></html>


<?php 
ob_end_flush();
?>


<frame src="printApplicationForm.php?workflow_settings=<?php echo $_GET["workflow_settings"]?>&title=<?php echo $_GET["title"]?>&appid=<?php echo $_GET["appid"]?>" name="bodyFrame" id="bodyFrame" marginwidth=0 marginheight=0 frameborder=0 noresize scrolling=auto>