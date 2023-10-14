<?php 
$run_in_script_mode = true;
$path = '../';
require_once ('/var/www/html/common/_systems/heqc-online.php');

function doOutPutBuffer ($buffer) {
	$search_array = array("/\<script\>.*\<\/script\>/sU", "/(\<a.*[^>]href=.*(?:changeCMD).*\>)(.*)(\<\/a\>)/U");
	$replace_array = array("", "\\2");

	$html = $buffer;
	$html = preg_replace ($search_array, $replace_array, $buffer);

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
//	if ($pair[0] == "DBINF_Institutions_application___application_id") {
//		$current_user_id = $app->getValueFromTable("Institutions_application", "application_id", $pair[1], "user_ref");
//	}
}

$current_user_id = $app->getInstitutionAdministrator(0,$inst_id);

// STUPID DUMP CODE replaced by the line above.
// $inst_id = substr($workflow_arr[2], (strPos($workflow_arr[2], "institution_ref=")+16), strlen($workflow_arr[2]));

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
	<td height="2" align="right"><?php echo '<a align="right" href="javascript:window.print();">Print</a>'; ?>
	</td>
</tr>
</table>
<?php 
// Changed by Rebecca & Robin - 12/12/2006 - main site info was not displaying

	$forms_begin = array ("instProfile1","instProfile1_contacts","instProfile1_contact_a","instProfile1_contact_r","instProfileContactInfoPagePerSite","General_Policies");
	$if_forms = array();
	$forms_end = array("instProfile18", "instProfile19", "instProfile20", "instProfile21", "instProfile22", "instProfile23", "instProfile24", "instProfile12");
	$forms_private = array();

	if ($app->getValueFromTable("institutional_profile", "institution_ref", $inst_id, "mode_delivery") == 2) {
		array_push($if_forms, "instProfile27");
	}
	if ($app->getValueFromTable("institutional_profile", "institution_ref", $inst_id, "mode_delivery") == 4) {
		array_push($if_forms, "instProfile26");
	}
	if ($app->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "priv_publ") == 1) {
		$forms_private = array("instProfile13", "instProfile14", "instProfile15", "instProfile16", "instProfile17", "instProfile28", "instProfile29");
	}
	$forms = array_merge($forms_begin, $if_forms, $forms_end, $forms_private);//array ("instProfile1", "instProfile2", "instProfile27", "instProfile26", "instProfile18", "instProfile19", "instProfile20", "instProfile21", "instProfile22", "instProfile23", "instProfile24", "instProfile12", "instProfile13", "instProfile14", "instProfile15", "instProfile16", "instProfile17", "instProfile28");

	foreach ($forms as $form) {
		$run_in_script_mode = true;
		$app = new HEQConline (1);
		$app->parseWorkFlowString($str);
		$app->template = $form;
		$app->view = 1;
		$app->formStatus = FLD_STATUS_TEXT;
		$app->readTemplate();

		// we need to set the current user id as the temp call can not figure
		//it out on its own.
		$app->currentUserID = $current_user_id;
		$app->createHTML($app->body);
		unset ($app);
	}
?>
</body></html>
<?php 
ob_end_flush();
?>


