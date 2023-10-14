<?php 

/*
Reyno
2004/4/21
This function displays a report for a application ()
*/

	$path="../";
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	$dbConnect = new dbConnect();
	$doc = new handleDocs();
	$type = readGET("type");

?>
<title>Report</title>
<?php/* <?php echo $path?>styles.css" title="Normal Style"> */ ?>
<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td bgcolor="#CC3300" height="2"></td></tr><tr><td bgcolor="#ECF1F6" align="center"><img src="<?php echo $path?>images/help_top.gif" width="255" height="45"></td></tr></table><br>
</head>
<table width="90%" align="center"><tr><td>
<?php 
if ($type == "paperEval")	echo $doc->makeSinglePaperEval($id);
if ($type == "site")	echo $doc->genSiteVisitReportPerApplication($id);
?>
</td></tr></table>
