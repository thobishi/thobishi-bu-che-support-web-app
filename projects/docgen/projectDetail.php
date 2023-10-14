<?php
	require_once ('_systems/che_projects.php');
	octoDB::connect ();
	writeXMLhead ();
?>
<?php
	function fmt_value($val){

		$fmt = ($val > "") ? str_replace("\r\n","<br />",$val) : "&nbsp;";
		if ($fmt == '1970-01-01') $fmt = "&nbsp;";

		return $fmt;
	}

	function writeReportValue($head,$val){
		$fmt = "";

		$fmt .= '<tr>';
		$fmt .= '   <td width="25%" bgcolor="5"><font size="10" color="#000000"><b>'.$head.'</b></font></td>';
		$fmt .= "   <td>".fmt_value($val)."</td>";
		$fmt .= "</tr>";

		return $fmt;
	}

	function displayProjectDetail($row){
		$rpt = "";

		$rpt .= '<p>'."\r\n";
		$rpt .= '<font size="12" color="#454444"><b>Project: '. $row["project_short_title"] . "</b></font></p>";
		$rpt .= '<p after="2">'."\r\n";
		$rpt .= '<table width="100%" border="t,b">';

		$rpt .= writeReportValue("Programme", getDirectorate($row["directorate_ref"]));
		$rpt .= writeReportValue("Category", getCategory($row["category_ref"]));
		$rpt .= writeReportValue("Project title (short)",$row["project_short_title"]);
		$rpt .= writeReportValue("Project title (full)",$row["project_full_title"]);
		$rpt .= writeReportValue("Core Mandate <br />(in order of relevance)",getCoreMandate($row["project_id"]));
		$rpt .= writeReportValue("Planned start date",$row["planned_start_date"]);
		$rpt .= writeReportValue("Planned end date",$row["planned_end_date"]);
		$rpt .= writeReportValue("Budget (per year)", displayBudgetPerYear($row["project_id"]));
		$rpt .= writeReportValue("Background and rationale",$row["background_rationale"]);
		$rpt .= writeReportValue("Project goals",$row["goals"]);
		$rpt .= writeReportValue("Deliverables and planned outputs",$row["deliverables"]);
		$rpt .= writeReportValue("Project Team",getProjectTeam($row["project_id"]));
		$rpt .= writeReportValue("Outputs", displayOutputs($row["project_id"]));


		$rpt .= '</table>';
		$rpt .= "\r\n";
		$rpt .= '</p>'."\r\n";

		return $rpt;
	}

	function displayEventDetail($row){
		$rpt = "";

		$rpt .= '<p />'."\r\n";
		$rpt .= '<font size="12" color="#454444"><b>Event: '. $row["project_short_title"] . "</b></font>";
		$rpt .= '<p after="2" />'."\r\n";
		$rpt .= '<table width="100%" border="t,b">';

		$rpt .= writeReportValue("Programme", getDirectorate($row["directorate_ref"]));
		$rpt .= writeReportValue("Category", getCategory($row["category_ref"]));
		$rpt .= writeReportValue("Project code",$row["proj_code"]);
		$rpt .= writeReportValue("Project title (short)",$row["project_short_title"]);
		$rpt .= writeReportValue("Project title (full)",$row["project_full_title"]);
		$rpt .= writeReportValue("Role players involved",$row["role_players_involved"]);
		$rpt .= writeReportValue("Budget (per year)", displayBudgetPerYear($row["project_id"]));
		$rpt .= writeReportValue("Outputs", displayOutputs($row["project_id"]));
		$rpt .= '</table>';
		$rpt .= "\r\n";
		$rpt .= '<p />'."\r\n";

		return $rpt;
	}

	function displayActivityDetail($row){
		$rpt = "";

		$rpt .= '<p />'."\r\n";
		$rpt .= '<font size="12" color="#454444"><b>Activity: '. $row["project_short_title"] . "</b></font>";
		$rpt .= '<p after="2" />'."\r\n";
		$rpt .= '<table width="100%" border="t,b">';

		$rpt .= writeReportValue("Programme", getDirectorate($row["directorate_ref"]));
		$rpt .= writeReportValue("Category", getCategory($row["category_ref"]));
		$rpt .= writeReportValue("Project code",$row["proj_code"]);
		$rpt .= writeReportValue("Project title (short)",$row["project_short_title"]);
		$rpt .= writeReportValue("Project title (full)",$row["project_full_title"]);
		$rpt .= writeReportValue("Key processes and/or phases",$row["key_processes_phases"]);
		$rpt .= writeReportValue("Role players involved",$row["role_players_involved"]);
		$rpt .= writeReportValue("Budget (per year)", displayBudgetPerYear($row["project_id"]));
		$rpt .= writeReportValue("Outputs", displayOutputs($row["project_id"]));

		$rpt .= '</table>';
		$rpt .= "\r\n";
		$rpt .= '<p />'."\r\n";

		return $rpt;
	}

	function getDirectorate ($dir){

		$htm = "";

		$sql = <<<sqlM
			SELECT *
			FROM lkp_directorate
			WHERE lkp_directorate_id = $dir
sqlM;

		$rs = mysqli_query($sql) or die(mysqli_error());
		$row = mysqli_fetch_array($rs);
		$dir_desc = $row["directorate_description"];

		return $dir_desc;
	}

	function getCategory ($cat){

			$htm = "";

			$sql = <<<sqlM
				SELECT *
				FROM lkp_project_categories
			    WHERE category_id = $cat
sqlM;

			$rs = mysqli_query($sql) or die(mysqli_error());
			$row = mysqli_fetch_array($rs);
			$cat_desc = $row["category_desc"];

			return $cat_desc;
	}

	function getCoreMandate ($prj){

		$htm = "";

		$sql = <<<sqlM
			SELECT *
			FROM project_detail_mandate,
				lkp_che_mandate
			WHERE project_detail_mandate.che_mandate_ref = lkp_che_mandate.lkp_che_mandate_id
			AND project_detail_mandate.project_ref = '$prj'
			ORDER BY relevance_ref
sqlM;

		$rs = mysqli_query($sql) or die(mysqli_error());

		while ($row = mysqli_fetch_array($rs)){
			$htm .= '<p lindent="5" findent="-6" /><font face="symbol">&amp;#U183</font><tab />';
			$htm .= $row["mandate_full"];

		}

		return $htm;
	}

	function getProjectTeam($prj){
		$htm = "";

		$tsql = <<<tsql
		SELECT * FROM project_team
		WHERE project_ref = '$prj';
tsql;
		$trs = mysqli_query($tsql) or die(mysqli_error());

		if (mysqli_num_rows($trs) > 0){
		$ordered_list = "1";

			while ($trow = mysqli_fetch_array($trs))
			{
				$htm .= '<p lindent="5" findent="-6" />'.$ordered_list.'.<tab />';
/*
				$htm .= fmt_value($trow["personnel_title"])." ".fmt_value($trow["personnel_name"])." ".fmt_value($trow["personnel_surname"]);
				$htm .= " - <b>".fmt_value($trow["role"])."</b>";
				$htm .= "<br />";
				$htm .= "<i> (".fmt_value($trow["email"]);

				$htm .= (fmt_value($trow["work_telephone_no"]) != "") ? "; ".fmt_value($trow["work_telephone_no"]) : "";
				$htm .= ")</i>";
*/
				$htm .= fmt_value($trow["personnel_name"]);
				$htm .= " - <b>".fmt_value($trow["role"])."</b>";

				$ordered_list++;
			}

		} else {

			$htm .= "<i>There is no project team data.</i>";
		}
		return $htm;

	}

//Rebecca: 2007-09-27
//Displays the planned budget per year
function displayBudgetPerYear($proj_id) {
		$budgetArr = array();
		$yearArr = array();
		$budget_table = "";

		$ySQL = <<<PSQL
			SELECT r.budget_year, r.proj_code, r.proj_description, b.planned_budget, b.revised_budget
			FROM project_required_list as r
			LEFT JOIN project_budget_per_year as b
				ON (r.budget_year = b.budget_year AND r.project_ref = b.project_ref)
			WHERE r.project_ref = $proj_id
PSQL;
		$yRS = mysqli_query($ySQL);

		if ($yRS && mysqli_num_rows($yRS) > 0) {

				$budget_table  = "<p>";
				$budget_table .= "<table border='t,b,l,r' width='70%'>";
				$budget_table .= "<tr>";
			$budget_table .= "<td width='12%'><b>Year</b></td>";
				$budget_table .= "<td width='12%'><b>Project<br />code</b></td>";
				$budget_table .= "<td width='24%'><b>Project<br />description</b></td>";
				$budget_table .= "<td width='15%'><b>Planned Budget</b></td>";
				$budget_table .= "<td width='15%'><b>Revised Budget</b></td>";
				$budget_table .= "<td width='20%'><b>Expenditure (ytd)</b></td>";

				$budget_table .= "</tr>";
				while ($yRow = mysqli_fetch_array($yRS)) {
					$by = $yRow['budget_year'];
					$proj_code = $yRow["proj_code"];
					$proj_desc = $yRow["proj_description"];
					$expenditure = CHEProjects::calculateExpenditure($by, $proj_code);
					$budget_table .= "<tr>";
					$budget_table .= "<td>".$by."</td><td>".$proj_code."</td><td>".$proj_desc."</td><td>R ".sprintf("%d",$yRow['planned_budget'])."</td><td>R ".sprintf("%d",$yRow['revised_budget'])."</td><td>R ".sprintf("%01.2f",$expenditure)."</td>";
					$budget_table .= "</tr>";
				}

				$budget_table .= "</table></p>";
			}
			else {
				$budget_table = "Project not listed for financial information.";
			}

			return $budget_table;
}

//Rebecca: 2007-09-27
//Displays the outputs per year in a table
function displayOutputs($proj_id){

      $output_table = "";

	  	$SQL = "SELECT * FROM project_detail_per_year WHERE project_ref=".$proj_id;
	  	$rs = mysqli_query($SQL);

			if ($rs && mysqli_num_rows($rs) > 0) {

			while ($row = mysqli_fetch_array($rs)) {
				$output_table .= "<table border='0' cellpadding='2' cellspacing='2' width='100%'>";
				$output_table .= "<tr><td colspan='2'>Budget year: ".fmt_value($row['budget_year'])."</td></tr>";
				$output_table .= "<tr><td width='22%' valign='top'>Capacity development</td>\r\n";
				$output_table .= "<td width='48%' bgcolor='white'>".fmt_value($row['capacity_development'])."</td>";
				$output_table .= "</tr>";
				$output_table .= "<tr><td width='22%' valign='top'>Stakeholder feedback</td>\r\n";
				$output_table .= "<td width='48%' bgcolor='white'>".fmt_value($row['stakeholder_feedback'])."</td>";
				$output_table .= "</tr>";
				$output_table .= "<tr><td width='22%' valign='top'>Outputs achieved</td>\r\n";
				$output_table .= "<td width='48%' bgcolor='white'>".fmt_value($row['outputs_deliverables'])."</td>";
				$output_table .= "</tr>";

				}

				$output_table .= "</table>";

			}
	  	else {
			$budget_table = "Project not listed for outputs.";
		}

	return $output_table;
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
comment="">

<?php

	$userid = readGET("user");
	$project_ref = readGET("project");
	$directorate_ref = readGET("directorate");
	$category_ref = readGET("category");

	$sec = CHEProjects::getSecurityAccess($userid);
	$dir_title = "";
	$cat_title = "";
	$msg_cat = "";
	$msg_dir = "";

    $whereArr = array(1);


	// Users are restricted as to which projects they may see
	   if ($sec["filter"] > ""){
			array_push ($whereArr, $sec["filter"]);
		}

		if ($project_ref > 0){
			array_push($whereArr,"project_id = '".$project_ref."'");
	    }
		if ($category_ref > 0){
			array_push($whereArr,"category_ref = '".$category_ref."'");
			$cat_title  = dbConnect::getValueFromTable("lkp_project_categories","category_id", $category_ref, "category_desc");
		  }
		  else{

		  $msg_cat = "All categories";
		}

		if ($directorate_ref > 0){
			array_push($whereArr,"directorate_ref = '".$directorate_ref."'");
			$dir_title = dbConnect::getValueFromTable("lkp_directorate","lkp_directorate_id", $directorate_ref, "directorate_description");
		 }
		 else{

			$msg_dir = "All programmes";
	    }




echo '<table border="l,r" width="98%">';

echo '<tr><td>';
echo '<img src="docgen/images/header.jpg" width="190" height="33" wrap="no" align="left" border="0" left="-2" top="-2" anchor="INCELL" />';
echo '</td></tr>';

echo '<tr>';
echo '<td>';
echo '<br /><br /><br /><br /><br /><br /><br />';
echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';

echo '<p align="center"><font size="22" color="#000000" align="center">Project Register</font></p>';
echo '<p align="center"><font size="24" color="#50719c" align="center"><b>Project Detail Report</b></font></p><br />';
echo ($cat_title) ? '<p align="center"/><font size="18" color="#50719c" align="center"><b><i><br />- '.$cat_title.' - </i></b></font>' : '<p align="center"/><font size="18" color="#50719c" align="center"><b><i><br />- '.$msg_cat.' -</i></b></font><br />';
echo ($dir_title) ? '<p align="center"/><font size="18" color="#50719c" align="center"><b><i><br />- '.$dir_title.' - </i></b></font><br />' : '<p align="center"/><font size="18" color="#50719c" align="center"><b><i><br />- '.$msg_dir.' - </i></b></font><br />';
echo '<p align="center"><font size="16" color="#000000" align="center"><i>Generated on '.date("j F Y").'</i></font></p><br /><br />';
echo '<br /><br />';
echo '<p align="center"><font size="20" color="#000000" align="center">Council on Higher Education</font></p><br /><br />';
echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';


echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<td valign="bottom"><img src="docgen/images/footer.jpg" width="190" height="5" wrap="no" border="0" left="-2" top="1" anchor="INCELL" /></td>';
echo '</tr>';
echo '</table>';

echo '<section />';

echo '<header><b>Project Register - Project Detail Report</b></header>';
echo '<footer><table border="0"><tr><td align="left">';
echo '<font size="10"><b>Council on Higher Education</b><tab /></font></td><td align="right"><cpagenum />/<tpagenum /><img src="docgen/images/footer.jpg" width="210" height="10" wrap="no" align="center" border="0" left="0" top="290" anchor="page" />';
echo '</td></tr></table></footer>';


	$where = "WHERE " . implode(" AND ", $whereArr);

	$sql = "SELECT * FROM project_detail " . $where . " ORDER BY directorate_ref, project_short_title";
	$rs = mysqli_query($sql);

	if (mysqli_num_rows($rs) > 0){
	$new_page = 'true';

	while ($row = mysqli_fetch_array($rs)){
	 echo ($new_page != 'true') ? '<page />' : '';
	 $CATG_ref = $row['category_ref'];

			Switch($CATG_ref)
			{

			   case "1":
			    echo displayProjectDetail($row);
			    $new_page = 'false';
			   break;

		       case "2":
		        echo displayActivityDetail($row);
		        $new_page = 'false';

		       break;

		       case "3":
		        echo displayEventDetail($row);
		        $new_page = 'false';


			}
		}

echo $sql;
die();
   	}


?>
</DOC>
