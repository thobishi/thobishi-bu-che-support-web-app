<?php
	require_once ('_systems/che_projects.php');
	octoDB::connect ();
	writeXMLhead ();
?>
<?php

	function displayReportCoverPage($title) {
		echo '<table border="l,r" width="100%">';

		echo '<tr><td>';
		echo '<img src="docgen/images/header.jpg" width="190" height="33" wrap="no" align="left" border="0" left="-2" top="-2" anchor="INCELL" />';
		echo '</td></tr>';

		echo '<tr>';
		echo '<td>';
		echo '<br /><br /><br /><br /><br /><br /><br />';
		echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';

		echo '<p align="center"/><font size="24" color="#000000" align="center">Project Register</font>';
		echo '<p align="center"/><br /><font size="26" color="#50719c" align="center"><b>'.$title.'</b></font>';
		echo '<p align="center"/><br /><br /><font size="16" color="#000000" align="center"><i>Generated on '.date("j F Y").'</i></font>';
		echo '<br /><br />';
		echo '<p align="center"/><br /><br /><font size="20" color="#000000" align="center">Council on Higher Education</font>';
		echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';

		echo '</td>';
		echo '</tr>';

		echo '<tr>';
		echo '<td valign="bottom"><img src="docgen/images/footer.jpg" width="190" height="5" wrap="no" border="0" left="-2" top="1" anchor="INCELL" /></td>';
		echo '</tr>';
		echo '</table>';
	}

	function displayGeneralPageSetup($title) {
		echo '<header><b>Project Register - '.$title.'</b></header>';
		echo '<footer><table border="0" width="140%"><tr><td align="left">';
		echo '<font size="10"><b>Council on Higher Education</b><tab /></font></td><td align="right"><cpagenum />/<tpagenum /><img src="docgen/images/footer.jpg" width="210" height="10" wrap="no" align="center" border="0" left="0" top="290" anchor="page" />';
		echo '</td></tr></table></footer>';
	}

?>

<DOC
config_file="docgen/doc_config.inc"
title="Project Budget and Expenditure Report"
subject=""
author="Project Register"
manager=""
company="Council on Higher Education"
operator=""
category="Project Budget and Expenditure"
keywords="project budget report"
comment=""
>

<?php

$title = "Project Budget and Expenditure Report";
//set variables for document
	$budget_year = readGET("budget");
	$project_source = readGET("proj_source");
	$userid = readGET("user");
	$sec = CHEProjects::getSecurityAccess($userid);

displayReportCoverPage($title);

echo '<section landscape="yes" />';
displayGeneralPageSetup($title);

	$gt_planned_budget = 0;
	$gt_revised_budget = 0;
	$gt_q1_budget = 0;
	$gt_q2_budget = 0;
	$gt_q3_budget = 0;
	$gt_q4_budget = 0;
	$gt_expenditure = 0;
	$prev_directorate = "-1";

//setting SQL for generating document

$sqlP = CHEprojects::getProjectList($project_source,$sec["filter"],$budget_year);

	$pRs = mysqli_query($sqlP) or die(mysqli_error());
	if (mysqli_num_rows($pRs) > 0){
		$new_page = 'true';
		$prevDirectorate = "-1";

		echo '<table border="t,b,l,r" width="140%">';

		while ($pRow = mysqli_fetch_array($pRs))
		{

			if ($pRow["directorate_ref"] <> $prevDirectorate)
			{
				$dir_description = CHEprojects::getDirectorate($pRow["directorate_ref"]);

		// Print totals for previous directorate
				if ($prevDirectorate != -1)
				{

					$t_pSpent = ($t_planned_budget > 0) ? sprintf("%d", ($t_expenditure / $t_planned_budget)*100) : "&nbsp;";
					$t_rSpent = ($t_revised_budget > 0) ? sprintf("%d", ($t_expenditure / $t_revised_budget)*100) : "&nbsp;";

					echo "<tr>";
					echo "<td>&nbsp;</td>";
					echo "<td><b>Total: ".CHEprojects::getDirectorate ($prevDirectorate)."</b></td>";
					echo '<td align="right"><b>'.sprintf("%d",$t_planned_budget)."</b></td>";
					echo '<td align="right"><b>'.sprintf("%d",$t_pSpent)."</b></td>";
					echo '<td align="right"><b>'.sprintf("%d",$t_revised_budget)."</b></td>";
					echo '<td align="right"><b>'.sprintf("%d",$t_rSpent)."</b></td>";
					echo '<td align="right"><b>'. sprintf("%01.2f",$t_q1_budget) ."</b></td>";
					echo '<td align="right"><b>'. sprintf("%01.2f",$t_q2_budget) ."</b></td>";
					echo '<td align="right"><b>'. sprintf("%01.2f",$t_q3_budget) ."</b></td>";
					echo '<td align="right"><b>'. sprintf("%01.2f",$t_q4_budget) ."</b></td>";
					echo '<td align="right"><b>'.sprintf("%01.2f",$t_expenditure)."</b></td>";
					echo "</tr>";

					$gt_planned_budget += $t_planned_budget;
					$gt_revised_budget += $t_revised_budget;
					$gt_q1_budget += $t_q1_budget;
					$gt_q2_budget += $t_q2_budget;
					$gt_q3_budget += $t_q3_budget;
					$gt_q4_budget += $t_q4_budget;
					$gt_expenditure += $t_expenditure;


				}

				/* Initialise totals for directorate*/
				$t_planned_budget = 0;
				$t_revised_budget = 0;
				$t_q1_budget = 0;
				$t_q2_budget = 0;
				$t_q3_budget = 0;
				$t_q4_budget = 0;
				$t_expenditure = 0;

				echo '<tr>';
				echo '<td bgcolor="5" colspan="2" width="25%"><font size="10" color="#000000"><b>Programme: </b></font>'.$dir_description.'</td>';
				echo '<td bgcolor="5" colspan="4" width="30%"><font size="10" color="#000000"><b>Budget</b></font></td>';
				echo '<td bgcolor="5" colspan="5" width="45%"><font size="10" color="#000000"><b>Expenditure</b></font></td>';
				echo "</tr>";

				echo '<tr>';
				echo '<td bgcolor="5" width="5%"><font size="10" color="#000000"><b>No</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>Item</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>Planned</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>% Spent</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>Revised</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>% Spent</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>Ytd June</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>Ytd Sept</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>Ytd Dec</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>Ytd Mar</b></font></td>';
				echo '<td bgcolor="5"><font size="10" color="#000000"><b>Ytd Expenses</b></font></td>';
				echo "</tr>";

				$prevDirectorate = $pRow["directorate_ref"];

			}

		$rpt = "";

		$registered = "Not registered in Project Register";
		$short_title = $pRow["proj_description"];
		$planned_budget = 0;
		$revised_budget = 0;
		$planned_start_date = "";
		$planned_end_date = "";
		$project_id = ($project_source == 2) ? $pRow["project_ref"] : $pRow["project_id"];

	// get Project Detail
		$bSQL = <<<SQL
			SELECT *
			FROM `project_detail`
			WHERE project_id = $project_id
SQL;

		$rs = mysqli_query($bSQL) or die(mysqli_error());
		if (mysqli_num_rows($rs) > 0){
			$prj_row = mysqli_fetch_array($rs);
			$registered = "";
			$budget = CHEProjects::getBudget($budget_year,$prj_row["project_id"]);
			$planned_budget = $budget["planned"];
			$revised_budget = $budget["revised"];
			$planned_start_date = $prj_row["planned_start_date"];
			$planned_end_date = $prj_row["planned_end_date"];
			$short_title = $prj_row["project_short_title"];
		}

		$expenditure = CHEprojects::calculateExpenditure($budget_year, $pRow["proj_code"]);

		$qB = CHEprojects::calculateQuarterlyExpenditure($budget_year, $pRow["proj_code"]);

		$expenditure = ($expenditure==0) ? '&nbsp;' : sprintf("%01.2f", $expenditure);
		$planned_budget = ($planned_budget==0) ? '&nbsp;' : sprintf("%d", $planned_budget);
		$revised_budget = ($revised_budget==0) ? '&nbsp;' : sprintf("%d", $revised_budget);
		$qB1 = ($qB[1]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[1]);
		$qB2 = ($qB[2]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[2]);
		$qB3 = ($qB[3]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[3]);
		$qB4 = ($qB[4]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[4]);

		$pSpent = ($planned_budget > 0) ? sprintf("%d", ($expenditure / $planned_budget)*100) : "&nbsp;";
		$rSpent = ($revised_budget > 0) ? sprintf("%d", ($expenditure / $revised_budget)*100) : "&nbsp;";

		$rpt .= "<tr>";
		$rpt .= "<td>".$pRow["proj_code"]."</td>";
		$rpt .= "<td>".$short_title."</td>";
		if ($registered == ""){
			$rpt .=  '<td align="right">'.$planned_budget."</td>";
			$rpt .=  '<td align="right">'.$pSpent."</td>";
			$rpt .=  '<td align="right">'.$revised_budget."</td>";
			$rpt .=  '<td align="right">'.$rSpent."</td>";
			$rpt .= '<td align="right">'. $qB1 ."</td>";
			$rpt .= '<td align="right">'. $qB2 ."</td>";
			$rpt .= '<td align="right">'. $qB3 ."</td>";
			$rpt .= '<td align="right">'. $qB4 ."</td>";
		} else {
			$rpt .= '<td align="left" colspan="5">'.$registered."</td>";
		}
		$rpt .= '<td align="right">'.$expenditure."</td>";
		$rpt .= "</tr>";

		echo $rpt;
			// Accumulate totals for the directorate
				$t_planned_budget += $planned_budget;
				$t_revised_budget += $revised_budget;
				$t_q1_budget += $qB1;
				$t_q2_budget += $qB2;
				$t_q3_budget += $qB3;
				$t_q4_budget += $qB4;
				$t_expenditure += $expenditure;

		}

		// display totals for last directorate
			$t_pSpent = ($t_planned_budget > 0) ? sprintf("%d", ($t_expenditure / $t_planned_budget)*100) : "&nbsp;";
			$t_rSpent = ($t_revised_budget > 0) ? sprintf("%d", ($t_expenditure / $t_revised_budget)*100) : "&nbsp;";

			echo "<tr>";
			echo "<td>&nbsp;</td>";
			echo "<td><b>Total: ".CHEprojects::getDirectorate ($prevDirectorate)."</b></td>";
			echo '<td align="right"><b>'. sprintf("%d",$t_planned_budget)."</b></td>";
			echo '<td align="right"><b>'.sprintf("%d",$t_pSpent)."</b></td>";
			echo '<td align="right"><b>'. sprintf("%d",$t_revised_budget)."</b></td>";
			echo '<td align="right"><b>'.sprintf("%d",$t_rSpent)."</b></td>";
			echo'<td align="right"><b>'. sprintf("%01.2f",$t_q1_budget) ."</b></td>";
			echo '<td align="right"><b>'. sprintf("%01.2f",$t_q2_budget) ."</b></td>";
			echo '<td align="right"><b>'. sprintf("%01.2f",$t_q3_budget) ."</b></td>";
			echo '<td align="right"><b>'. sprintf("%01.2f",$t_q4_budget) ."</b></td>";
			echo '<td align="right"><b>'. sprintf("%01.2f",$t_expenditure)."</b></td>";
			echo "</tr>";

			$gt_planned_budget += $t_planned_budget;
			$gt_revised_budget += $t_revised_budget;
			$gt_q1_budget += $t_q1_budget;
			$gt_q2_budget += $t_q2_budget;
			$gt_q3_budget += $t_q3_budget;
			$gt_q4_budget += $t_q4_budget;
			$gt_expenditure += $t_expenditure;
			$gt_pSpent = ($gt_planned_budget > 0) ? sprintf("%d", ($gt_expenditure / $gt_planned_budget)*100) : "&nbsp;";
			$gt_rSpent = ($gt_revised_budget > 0) ? sprintf("%d", ($gt_expenditure / $gt_revised_budget)*100) : "&nbsp;";

			echo "<tr>";
			echo "<td>&nbsp;</td>";
			echo "<td><b>TOTAL EXPENSES:</b></td>";
			echo '<td align="right"><b>'. sprintf("%d",$gt_planned_budget)."</b></td>";
			echo '<td align="right"><b>'. sprintf("%d",$gt_pSpent)."</b></td>";
			echo '<td align="right"><b>'. sprintf("%d",$gt_revised_budget)."</b></td>";
			echo '<td align="right"><b>'. sprintf("%d",$gt_rSpent)."</b></td>";
			echo '<td align="right"><b>'. sprintf("%01.2f",$gt_q1_budget) ."</b></td>";
			echo '<td align="right"><b>'. sprintf("%01.2f",$gt_q2_budget) ."</b></td>";
			echo '<td align="right"><b>'. sprintf("%01.2f",$gt_q3_budget) ."</b></td>";
			echo '<td align="right"><b>'. sprintf("%01.2f",$gt_q4_budget) ."</b></td>";
			echo '<td align="right"><b>'. sprintf("%01.2f",$gt_expenditure)."</b></td>";
			echo "</tr>";

		echo "</table>";
	}
?>
</DOC>
