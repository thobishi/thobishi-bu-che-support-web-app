<?php 
/*
Rebecca
2007-12-12
Displays a history of the AC meetings that an application went through
*/

	$path="../";

	require_once ("/var/www/html/common/_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	$app = new HEQConline (1);
	$app_id = readGET("app_ref");
	$app_id = base64_decode($app_id);
?>

<title>Report</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
<script language="JavaScript" src="../js/che.js"></script>
<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td bgcolor="#CC3300" height="2"></td></tr><tr><td bgcolor="#ECF1F6" align="center"><img src="<?php echo $path?>images/help_top.gif" width="255" height="45"></td></tr></table><br>
</head>
<table width="98%" cellspacing="2" cellpadding="2" align="center" border=0>
<tr><td colspan='10'><?php $app->getApplicationInfoTableTop($app_id, "../");?></td></tr>
<tr><td>
<?php 

echo $app->get_outcome_history($app_id);

?>
</td></tr></table>


