<?php
	require_once ('_systems/che_projects.php');
	octoDB::connect ();
	writeXMLhead ();
?>
<?php

function printCoverPage($title, $genDate) {
	$coverPage =<<< COVER
		<table border="l,r" width="100%">
		<tr><td>
			<img src="docgen/images/header.jpg" width="190" height="33" wrap="no" align="left" border="0" left="-2" top="-2" anchor="INCELL" />
		</td></tr>
		<tr>
			<td>
				<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
				<p align="center"/><font size="24" color="#000000" align="center">Project Register</font>
				<p align="center"/><br /><font size="26" color="#50719c" align="center"><b>$title</b></font>
				<p align="center"/><br /><br /><font size="16" color="#000000" align="center"><i>Generated on $genDate</i></font>
				<br /><br />
				<p align="center"/><br /><br /><font size="20" color="#000000" align="center">Council on Higher Education</font>
				<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
			</td>
		</tr>
		<tr>
			<td valign="bottom"><img src="../docgen/images/footer.jpg" width="190" height="5" wrap="no" border="0" left="-2" top="1" anchor="INCELL" /></td>
		</tr>
		</table>
COVER;
	return $coverPage;
}

?>

<DOC
config_file="docgen/doc_config.inc"
title="Project Detail Report"
subject=""
author="Project Register"
manager=""
company="Council on Higher Education"
operator=""
category="Project Detail"
keywords="project detail report"
comment=""
>

<?php

	$genDate = date("j F Y");
	$dir_title = "Performance Indicators Report";

	$budget_year = readGET("budget_year");;
	$budget_yearID = readGET("budget_id");;
	$userid = readGET("userid");
//	echo $budget_yearID;
	$sec = CHEprojects::getSecurityAccess($userid);

echo printCoverPage($dir_title, $genDate);

$pageSetup =<<<SETUP
	<header><b>Project Register - Performance Indicators Report</b></header>
	<footer><table border="0" align="center"><tr><td align="left">
	<font size="10"><b>Council on Higher Education</b><tab /></font></td><td align="right"><cpagenum />/<tpagenum /><img src="docgen/images/footer.jpg" width="210" height="10" wrap="no" align="center" border="0" left="0" top="290" anchor="page" />
	</td></tr></table></footer>
SETUP;
echo $pageSetup;

CHEprojects::displayProjectIndicatorReport("docgen", $sec, $budget_yearID, $budget_year);


?>
</DOC>