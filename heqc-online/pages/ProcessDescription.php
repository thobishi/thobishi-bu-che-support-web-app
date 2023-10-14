<?php 
/*
Reyno
2004/4/21
This function displays a report for a Application 
*/

	$path="../";
	require_once("/var/www/common/_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	$doc = new handleDocs();
	$id = readGET("id", 0);
?>
<html>
<head>
<link rel=STYLESHEET TYPE="text/css" href="../styles.css" title="Normal Style">
</head>
<title>Help</title>
<body class=help>
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td bgcolor="#CC3300" height="2"></td>
</tr><tr>
	<td bgcolor="#ECF1F6" align="center">
		<img src="../images/help_top.gif" width="255" height="45">
	</td>
</tr>
</table>
<?php echo $doc->getValueFromTable("processes","processes_id",$id,"processes_comment") ?>
</body></html>

