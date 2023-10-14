<?php 
$reportType = $_GET['reportType'];
$reportParam = $_GET['reportParam'];
$outcome = $_GET['outcome'];

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
$typeTitle = "";

switch($reportType) {
	case "accredited" :	$typeTitle = "Applications with AC outcomes";
						break;
	case "cancelled" :	$typeTitle = "Cancelled Applications";
						break;
	case "submitted" :	$typeTitle = "Submitted Applications";
						break;
	case "without" :	$typeTitle = "Unprocessed Submitted Applications";
						break;
	default :	$typeTitle = "Applications";
}

switch($reportParam) {
	case "private" :	$typeTitle .= " - private institutions";
						break;
	case "public" :		$typeTitle .= " - public institutions";
						break;
	default : 			$typeTitle .= " - overall total";
}

switch($outcome) {
	case "prov" :	$typeTitle .= ": Provisional Accreditation";
					break;
	case "provCond" :$typeTitle .= ": Provisional Accreditation with Conditions";
					break;
	case "not" :	$typeTitle .= ": Not Accredited";
					break;
	case "def" :	$typeTitle .= ": Deferred";
					break;
	default : 		$typeTitle .= "";
}

?>

<html>
<head>
<link rel=STYLESHEET TYPE="text/css" href="../styles.css" title="Normal Style">
</head>
<title>Detailed Report</title>
<script>
	function doFocus() {
		//self.close();
	}
</script>
<body>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td><span class="loud"><?php echo $typeTitle?></span><hr></td>
</tr>
<tr><td>

<?php 
	$sort_by_year = true;

	$app->reportSubmittedApplications('','0','0', '0',$reportType, $reportParam, 0, $outcome);

?>
</td></tr>
</table>

</body>
