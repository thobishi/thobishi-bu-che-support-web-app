<?php
	$path="../";

	require_once ("/var/www/common/_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	$app = new HEQConline (1);
	$app_proc_id = readGET("id");
	$app_proc_id = base64_decode($app_proc_id);
	$app_id = $app->getValueFromTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "application_ref");
?>

	<title>Background</title>
	<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
	<script language="JavaScript" src="../js/che.js"></script>
	<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td bgcolor="#CC3300" height="2"></td></tr><tr><td bgcolor="#ECF1F6" align="center"><img src="<?php echo $path?>images/help_top.gif" width="255" height="45"></td></tr></table><br>

	<table width="98%" cellspacing="2" cellpadding="2" align="center" border=0>
	<tr>
		<td colspan='10'>
			<?php $app->getApplicationInfoTableTop($app_id, "../"); ?>
		</td>
	</tr>
	<tr>
		<td>
			<br>
			<?php echo $app->displayBackgrounds($app_proc_id); ?>
		</td>
	</tr>
	</table>


