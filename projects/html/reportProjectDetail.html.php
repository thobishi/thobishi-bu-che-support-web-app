
<?php echo //$rpt = new reportGenerator();
function fmt_value($val){

	$fmt = ($val > "") ? simple_text2html($val) : "&nbsp;";
	if ($fmt == '1970-01-01') $fmt = "&nbsp;";

	return $fmt;
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

	$htm .= "<table>";

	while ($row = mysqli_fetch_array($rs)){
		$htm .= "<tr>";
		$htm .= "<td>-	".$row["mandate_full"]."</td>";
		$htm .= "<tr>";
	}

	$htm .= "</table>";

	return $htm;
}


  //Get projects
  $str = array();
  $sql = "SELECT project_full_title FROM project_detail WHERE category_ref = 1";
  $query = mysqli_query($sql) or die(mysqli_error());
  if(mysqli_num_rows($query) > 0){
  		while($rows = mysqli_fetch_array($query)){
  			$str[] = $rows['project_full_title' ];
  		}
  		$strBracket = "['";
  		$str .=  implode("','",$str);
  		$strBracket .= $str."']";
  }

function getProjectTeam($prj){
	$htm = "";

/* rtn 5/9/2007
project_personnel must still be loaded from the personnel system.  Personnel_refs are supposed to come through on the
project register application form but very few have so we're missing the link.  Just added personnel_names temporarily
until can get processes and data in place to be able to rather link it.
	$tsql = <<<tsql
		SELECT * FROM project_team, project_personnel
		WHERE project_team.personnel_ref = personnel_id
		AND project_ref = $prj;
tsql;
*/
	$tsql = <<<tsql
		SELECT * FROM project_team
		WHERE project_ref = $prj;
tsql;

	$trs = mysqli_query($tsql) or die(mysqli_error());

	$htm .= "<table cellpadding='1' cellspacing='2' border=0>";

	if (mysqli_num_rows($trs) > 0){

		$htm .= "<tr>";
		$htm .= "<td class='onblueblueb' width='20%'>Name</td>";
		$htm .= "<td class='onblueblueb' width='20%'>Role</td>";
		$htm .= "</tr>\n";

		while ($trow = mysqli_fetch_array($trs)){
			$htm .= "<tr bgcolor='white'>";
			$htm .= "<td>".fmt_value($trow["personnel_name"])."</td>";
			$htm .= "<td>".fmt_value($trow["role"])."</td>";
			$htm .= "</tr>\n";
		}

	} else {

		$htm .= "<tr><td colspan='4'>Not specified.</td></tr>";
	}

	$htm .= "</table>";

	return $htm;
}



//Rebecca: 2007-09-27
//Displays the outputs per year in a table
function displayOutputs($proj_id){
	$output_table = "";

	$SQL = "SELECT * FROM project_detail_per_year WHERE project_ref=".$proj_id;
	$rs = mysqli_query($SQL);

	while ($row = mysqli_fetch_array($rs)) {
		$output_table .= "<table border=0 cellpadding='2' cellspacing='2' width='100%'>\n";
		$output_table .= "<tr><td class='onblueblueb' colspan='2'>Budget year: ".$row['budget_year']."</td></tr>\n";

		$output_table .= "<tr><td class='onblueblueb' width='22%' valign='top'>Capacity development</td>";
		$output_table .= "<td bgcolor='white'>".fmt_value($row['capacity_development'])."</td>";
		$output_table .= "</tr>\n";
		$output_table .= "<tr><td class='onblueblueb' width='22%' valign='top'>Stakeholder feedback</td>";
		$output_table .= "<td bgcolor='white'>".fmt_value($row['stakeholder_feedback'])."</td>";
		$output_table .= "</tr>\n";
		$output_table .= "<tr><td class='onblueblueb' width='22%' valign='top'>Outputs achieved</td>";
		$output_table .= "<td bgcolor='white'>".fmt_value($row['outputs_deliverables'])."</td>";
		$output_table .= "</tr>\n";

		$output_table .= "</table>\n";
		$output_table .= "<br>\n";
	}


	return $output_table;
}

?>
<br>
<table width="99%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

This report displays information per project. The report may be run for a specific project or all projects.
To run the report for a specific project select the project from the drop down list and click on Create Report.
To run the report for all projects click on Create Report.
<br>
<br>
<?
	$userid = $this->currentUserID;
	$sec = $this->getSecurityAccess($userid);

	$project_ref = (isset($_POST['project_ref']) && $_POST['project_ref'] != "") ? $_POST['project_ref'] : "";
	$directorate_ref = (isset($_POST['directorate_ref']) && $_POST['directorate_ref'] != "") ? $_POST['directorate_ref'] : "";
	$category_ref = (isset($_POST['category_ref']) && $_POST['category_ref'] != "") ? $_POST['category_ref'] : "";
	$this->formFields["project_ref"]->fieldValue = $project_ref;
	$this->formFields["directorate_ref"]->fieldValue = $directorate_ref;
	$this->formFields["category_ref"]->fieldValue = $category_ref;

	$doc = new octoDocGen ("projectDetail", "user=".$userid."&project=".$project_ref."&directorate=".$directorate_ref."&category=".$category_ref);
	$doc->url ("Download Report");

?>
 will save the report as a rich text format document.
<br>
<br>

<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">

<tr align="right">
	<td>Select Category:</td>
	<td align="left"><?php echo echo $this->showField('category_ref'); ?></td>
</tr>
<tr align="right">
	<td>Select project:</td>
	<td align="left"><?php echo echo $this->showField('project_ref'); ?></td>
</tr>

<tr align="right">
	<td>Select programme:</td>
	<td align="left"><?php echo echo $this->showField('directorate_ref'); ?></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td align="left">
		<input type="submit" class="btn" name="submitButton" value="Create report" onClick="moveto('stay');">
	</td>
</tr>

</table>
<br>
<?

if (isset($_POST['submitButton']))
{
	$html = "";

	$whereArr = array(1);

	// Users are restricted as to which projects they may see
	if ($sec["filter"] > ""){
		array_push ($whereArr, $sec["filter"]);
	}

	if (isset($_POST['project_ref']) && $_POST['project_ref'] > 0){
		array_push($whereArr,"project_id = '".$_POST['project_ref']."'");
	}

	if (isset($_POST['directorate_ref']) && $_POST['directorate_ref'] > 0){
		array_push($whereArr,"directorate_ref = '".$_POST['directorate_ref']."'");
	}

    if (isset($_POST['category_ref']) && $_POST['category_ref'] > 0){
		array_push($whereArr,"category_ref = '".$_POST['category_ref']."'");
	}

	$where = "WHERE " . implode(" AND ", $whereArr);

	// order the projects within the directorates.
	$sql = "SELECT * FROM project_detail " . $where . " ORDER BY directorate_ref, project_short_title";

	$rs = mysqli_query($sql);
	if (mysqli_num_rows($rs) > 0){

	  while ($row = mysqli_fetch_array($rs)){
        $CATG_ref = $row['category_ref'];

	    Switch($CATG_ref)

	    {

		 case "1":

            $html .= "<span class='specialb'>".fmt_value($this->getValueFromTable("lkp_project_categories","category_id",$row["category_ref"],"category_desc"))."</span>";
		    $html .= "<span class='specialb'>: ". $row["project_short_title"] . "</span>";
		    $html .= "<p>";

		  	$html .= "<table width='95%' border='0' align='center' cellpadding='2' cellspacing='2'>";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Programme</td>";
		  	$html .= "<td>".fmt_value($this->getValueFromTable("lkp_directorate","lkp_directorate_id",$row["directorate_ref"],"directorate_description"))."</td></tr>\n";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Category</td>";
			$html .= "<td>".fmt_value($this->getValueFromTable("lkp_project_categories","category_id",$row["category_ref"],"category_desc"))."</td></tr>\n";
			$html .= "</tr>\n";
 //			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project code</td>";
 //			$html .= "<td>".fmt_value($row["proj_code"])."</td>";
 //			$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project title (short)</td>";
		  	$html .= "<td>".fmt_value($row["project_short_title"])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project title (full)</td>";
		  	$html .= "<td>".fmt_value($row["project_full_title"])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Core Mandate<br>(in order of relevance)</td>";
		  	$html .= "<td>".getCoreMandate($row["project_id"])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Planned start date</td>";
		  	$html .= "<td>".fmt_value($row["planned_start_date"])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Planned end date</td>";
		  	$html .= "<td>".fmt_value($row["planned_end_date"])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Budget (per year)</td>";
		  	$html .= "<td>".$this->displayBudgetPerYear($row['project_id'])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Background and rationale</td>";
		  	$html .= "<td>".fmt_value($row["background_rationale"])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project goals</td>";
		  	$html .= "<td>".fmt_value($row["goals"])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Beneficiaries and stakeholders</td>";
		  	$html .= "<td>".fmt_value($row["beneficiaries"])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Deliverables and planned outputs</td>";
		  	$html .= "<td>".fmt_value($row["deliverables"])."</td>";
		  	$html .= "</tr>\n";
		  	$html .= "<tr class='onblue' valign='top'>";
		  	$html .= "<td class='oncolourb' width='20%'>Project Team</td>";
		  	$html .= "<td>";
		  	$html .= getProjectTeam($row["project_id"]);
		  	$html .= "</td>";
		  	$html .= "</tr>";

		  	$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%' valign='top'>Outputs</td>";
		  	$html .= "<td>".displayOutputs($row["project_id"])."</td>";
		  	$html .= "</tr>\n";

		  	$html .= "</table>\n";

		    $html.= '<p class="pagebreak">&nbsp;</p>';
		  break;

		  case "2":

			$html .= "<span class='specialb'>".fmt_value($this->getValueFromTable("lkp_project_categories","category_id",$row["category_ref"],"category_desc"))."</span>";
			$html .= "<span class='specialb'>: ". $row["project_short_title"] . "</span>";
			$html .= "<p>";

			$html .= "<table width='95%' border='0' align='center' cellpadding='2' cellspacing='2'>";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Programme</td>";
			$html .= "<td>".fmt_value($this->getValueFromTable("lkp_directorate","lkp_directorate_id",$row["directorate_ref"],"directorate_description"))."</td></tr>\n";
			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Category</td>";
			$html .= "<td>".fmt_value($this->getValueFromTable("lkp_project_categories","category_id",$row["category_ref"],"category_desc"))."</td></tr>\n";
			$html .= "</tr>\n";
//			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project code</td>";
//			$html .= "<td>".fmt_value($row["proj_code"])."</td>";
//			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project title (short)</td>";
			$html .= "<td>".fmt_value($row["project_short_title"])."</td>";
			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project title (full)</td>";
			$html .= "<td>".fmt_value($row["project_full_title"])."</td>";
			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Key processes and/or phases</td>";
			$html .= "<td>".fmt_value($row["key_processes_phases"])."</td>";
			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Role players</td>";
			$html .= "<td>".fmt_value($row["role_players_involved"])."</td>";
			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Budget (per year)</td>";
			$html .= "<td>".$this->displayBudgetPerYear($row['project_id'])."</td>";
			$html .= "</tr>\n";

			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%' valign='top'>Outputs</td>";
			$html .= "<td>".displayOutputs($row["project_id"])."</td>";
			$html .= "</tr>\n";

			$html .= "</table>\n";

			$html.= '<p class="pagebreak">&nbsp;</p>';
		break;

		Case "3":

			$html .= "<span class='specialb'>".fmt_value($this->getValueFromTable("lkp_project_categories","category_id",$row["category_ref"],"category_desc"))."</span>";
		    $html .= "<span class='specialb'>: ". $row["project_short_title"] . "</span>";
			$html .= "<p>";

			$html .= "<table width='95%' border='0' align='center' cellpadding='2' cellspacing='2'>";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Programme</td>";
			$html .= "<td>".fmt_value($this->getValueFromTable("lkp_directorate","lkp_directorate_id",$row["directorate_ref"],"directorate_description"))."</td></tr>\n";
			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Category</td>";
			$html .= "<td>".fmt_value($this->getValueFromTable("lkp_project_categories","category_id",$row["category_ref"],"category_desc"))."</td></tr>\n";
			$html .= "</tr>\n";
//			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project code</td>";
//			$html .= "<td>".fmt_value($row["proj_code"])."</td>";
//			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project title (short)</td>";
			$html .= "<td>".fmt_value($row["project_short_title"])."</td>";
			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Project title (full)</td>";
			$html .= "<td>".fmt_value($row["project_full_title"])."</td>";
			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Role players involved</td>";
			$html .= "<td>".fmt_value($row["role_players_involved"])."</td>";
			$html .= "</tr>\n";
			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%'>Budget (per year)</td>";
			$html .= "<td>".$this->displayBudgetPerYear($row['project_id'])."</td>";
			$html .= "</tr>\n";

			$html .= "<tr class='onblue' valign='top'><td class='oncolourb' width='20%' valign='top'>Outputs</td>";
			$html .= "<td>".displayOutputs($row["project_id"])."</td>";
			$html .= "</tr>\n";

			$html .= "</table>\n";

		    $html.= '<p class="pagebreak">&nbsp;</p>';

		    }

		}

		echo $html;

//		$rpt->getData("sql",$sql);
//		$rpt->structureData("2",TRUE);
//		$rpt->writeData("doc");
//		$rpt->showData();
	}
	else
	{
		echo "<tr align='center'><td>No data has been found matching your criteria.</td></tr>";
	}



} // end if (isset($_POST['submitButton'])
?>
</td></tr>
</table>
<br>

