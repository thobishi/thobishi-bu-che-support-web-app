<?php 
/*
Reyno
2004/4/21
This page displays a report
*/

	$path="../";

	require_once ("/var/www/common/_systems/heqc-online.php");
	//require_once ("../../common/workflow-1.0/class.reports.php");
	$dbConnect = new dbConnect();
	$rep = new reports();
	$type = readGET("type");
	$category = readGET("category");
	$status = readGET("status");

?>
<title>Report</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td bgcolor="#CC3300" height="2"></td></tr><tr><td bgcolor="#ECF1F6" align="center"><img src="<?php echo $path?>images/help_top.gif" width="255" height="45"></td></tr></table><br>
</head>
<table width="90%" cellspacing="2" cellpadding="2" align="center"><tr><td>
<?php 

switch ($type){
	case "AC1": echo $rep->AC1(); break;
	case "AC2": echo $rep->AC2(); break;
	case "AC3": echo $rep->AC3(); break;
	case "AC4": echo $rep->AC4(); break;
	default : echo $rep->generalReport($category, $status);
}





//if ($type == "paperEval")	echo $doc->makeSinglePaperEval($id);
//if ($type == "site")	echo $doc->genSiteVisitReportPerApplication($id);
?>
</td></tr></table>
