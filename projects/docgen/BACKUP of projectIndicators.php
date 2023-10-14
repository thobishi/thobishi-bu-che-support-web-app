<?php
	require_once ('_systems/che_projects.php');
	octoDB::connect ();
	writeXMLhead ();
?>
<?php
	function fmt_value($val){

		$fmt = ($val > "") ? str_replace("\n","<br />",$val) : "&nbsp;";
		if ($fmt == '0000-00-00') $fmt = "";

		return $fmt;
	}

	function writeReportValue($head,$val, $align="align='left'"){
		$fmt = "";
		$fmt .= "<td ".$align.">".fmt_value($val)."</td>\n";
		return $fmt;
	}

	function displayProjectDetailPerRow($row, $budget_year){

		$rpt = "";
		$expenditure = CHEprojects::calculateExpenditure($budget_year, $row["proj_code"]);
		$budget = CHEprojects::getBudget($budget_year,$row["project_id"]);
		$revised_budget = $budget["revised"];
		$pSpent = ($revised_budget > 0) ? sprintf("%d", ($expenditure / $revised_budget)*100) : "0";
		$dir_description = CHEprojects::getDirectorate($row["directorate_ref"]);

		$rpt .= "<tr>";

		$rpt .= writeReportValue("Directorate",$dir_description);
		$rpt .= writeReportValue("Project code",$row["proj_code"]);
		$rpt .= writeReportValue("Title",$row["project_short_title"]);
		$rpt .= writeReportValue("Budget Year(s)",$row["budget_year"]);
		$rpt .= writeReportValue("Capacity Development",$row["capacity_development"]);
		$rpt .= writeReportValue("Stakeholder Feedback",$row["stakeholder_feedback"]);
		$rpt .= writeReportValue("Outputs/Deliverables",$row["outputs_deliverables"]);
		$rpt .= writeReportValue("Revised Budget (R)",sprintf("%01.2f", $revised_budget), "align='right'");
		$rpt .= writeReportValue("Expenditure (R)", sprintf("%01.2f", $expenditure), "align='right'");
		$rpt .= writeReportValue("% Spent", $pSpent);

		$rpt .= "</tr>";
		return $rpt;
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

	$budget_year = readGET("budget_year");
	$userid = readGET("userid");
	$sec = CHEProjects::getSecurityAccess($userid);
	$dir_title = "";

	$whereArr = array(1);

	// Users are restricted as to which projects they may see
	if ($sec["filter"] > ""){
		array_push ($whereArr, $sec["filter"]);
	}

	if ($budget_year > 0){
		array_push($whereArr,"budget_year = '".$budget_year."'");
	}


echo '<table border="l,r" width="100%">';

echo '<tr><td>';
echo '<img src="docgen/images/header.jpg" width="190" height="33" wrap="no" align="left" border="0" left="-2" top="-2" anchor="INCELL" />';
echo '</td></tr>';

echo '<tr>';
echo '<td>';
echo '<br /><br /><br /><br /><br /><br /><br />';
echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';

echo '<p align="center"/><font size="24" color="#000000" align="center">Project Register</font>';
echo '<p align="center"/><br /><font size="26" color="#50719c" align="center"><b>Performance Indicators Report</b></font>';
echo '<p align="center"/><br /><br /><font size="16" color="#000000" align="center"><i>Generated on '.date("j F Y").'</i></font>';
echo '<br /><br />';
echo '<p align="center"/><br /><br /><font size="20" color="#000000" align="center">Council on Higher Education</font>';
echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';

echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<td valign="bottom"><img src="docgen/images/footer.jpg" width="190" height="5" wrap="no" border="0" left="-2" top="1" anchor="INCELL" /></td>';
echo '</tr>';
echo '</table>';

echo '<section landscape="yes" />';

echo '<header><b>Project Register - Performance Indicators Report</b></header>';
echo '<footer><table border="0"><tr><td align="left">';
echo '<font size="10"><b>Council on Higher Education</b><tab /></font></td><td align="right"><cpagenum />/<tpagenum /><img src="docgen/images/footer.jpg" width="210" height="10" wrap="no" align="center" border="0" left="0" top="290" anchor="page" />';
echo '</td></tr></table></footer>';


	$sql = <<<SQL
			SELECT *
			FROM project_detail as d
SQL;

	$whereArr = array(1);
	$leftArr = array("y.project_ref = d.project_id");

	// Users are restricted as to which projects they may see
	if ($sec["filter"] > ""){
		array_push ($whereArr, $sec["filter"]);
	}

	if ($budget_year > ""){
		array_push($leftArr,"y.budget_year = '".$budget_year."'");
	}

	$left = " LEFT JOIN project_detail_per_year as y ON (". implode(" AND ", $leftArr) .")";
	$where = " WHERE " . implode(" AND ", $whereArr);
	$order = " ORDER BY directorate_ref, proj_code";

	$sql .= $left . $where . $order;

	$rs = mysql_query($sql);

	if (mysql_num_rows($rs) > 0){
		echo '<p after="5" />';
		echo '<table width="145%" border="t,b,l,r">';
		echo '<tr bgcolor="5">';
		echo '	<td><b>Directorate</b></td>';
		echo '	<td width="7%"><b>Project code</b></td>';
		echo '	<td><b>Title</b></td>';
		echo '	<td><b>Budget year(s)</b></td>';
		echo '	<td><b>Capacity Development</b></td>';
		echo '	<td><b>Stakeholder Feedback</b></td>';
		echo '	<td><b>Outputs/Deliverables</b></td>';
		echo '	<td width="10%"><b>Revised budget (R)</b></td>';
		echo '	<td width="11%"><b>Expenditure (R)</b></td>';
		echo '	<td width="5%"><b>% spent</b></td>';
		echo '</tr>';

		while ($row = mysql_fetch_array($rs)){
			echo displayProjectDetailPerRow($row, $budget_year);
		}

		echo "</table>";
		echo "<br /><br />";
		echo '<p />';
	}

?>
</DOC>