<?
$path = '../';
require_once ('_systems/che_projects.php');

$reportType = readGET('reportType');
$reportParam = readGET('reportParam');

$run_in_script_mode = true;

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

$app = new CHEprojects (1);

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
<tr><td>

<br>

<?
	$sort_by_year = true;

	$app->reportSubmittedApplications('','0','0',$reportType, $reportParam);

?>
</td></tr>
</table>

</body>
