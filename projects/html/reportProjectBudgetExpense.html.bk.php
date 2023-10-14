
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<b>This report provides a summary of the budget and expenditure per project.  Please note that the financial information is drawn from the financial
system on the third Monday of every month.  Thus the expenditure information reflected is not real-time.
<br>
<br>

<?

	// 4 October 2007: Robin
	// To implement security in a report you need:
	// - the userid of the current user.
	// - to run the getSecurityAccess($userid) function
	// - to pass the userid to the downlaodable report (cannot pick up the userid on the downlaodable report so need to pass it)

	$userid = $this->currentUserID;
	$sec = $this->getSecurityAccess($userid);

	$curr_month = date("n");
	$curr_year = date("Y");
	$financial_first_month = $this->getValueFromTable("lkp_financial_month", "financial_month", 1, "lkp_month_id");
	$financial_last_month = $this->getValueFromTable("lkp_financial_month", "financial_month", 12, "lkp_month_id");
	$curr_budget_year = ($curr_month < $financial_first_month) ? ($curr_year - 1) ."/". $curr_year : $curr_year ."/". ($curr_year + 1);

	$budget_year   = (isset($_POST['budget_year']) && $_POST['budget_year'] != "") ? $this->getValueFromTable("lkp_budget_year", "lkp_budget_year_id", $_POST['budget_year'], "lkp_budget_year") : $curr_budget_year;
//	$month   = (isset($_POST['month']) && $_POST['month'] != "") ? $_POST['month'] : $curr_month;
	$project_source = (isset($_POST['project_source']) && $_POST['project_source'] != "") ? $_POST['project_source'] : "1";
	$this->formFields["budget_year"]->fieldValue = $budget_year;
//	$this->formFields["month"]->fieldValue = $month;
	$this->formFields["project_source"]->fieldValue = $project_source;
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>Select source to use to select projects that will be included in the report.</td>
	<td>
		<?php echo $this->showField('project_source');	?>
	</td>
</tr>
<tr align="right">
	<td>
		Select the budget year for which you want the financial information:
	</td>
	<td align="left">
		<?php echo $this->showField('budget_year');	?>
	</td>
<!--
	<td width="15%">
		Month to date:
	</td>
	<td width="15%" align="left">
		<?// $this->showField('month');	?>
	</td>
-->
</tr>
<tr align="right">
<td colspan="4"><hr></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td align="left">
		<input type="submit" class="btn" name="submitButton" value="Create report" onClick="moveto('stay');">
	</td>
	<td>
		<?
//need to add month/project source eventually (Sept 2007)
			//$doc = new octoDocGen ("projectBudget", "budget=".$budget_year."&proj_source=".$project_source."&month=".$month);

			$doc = new octoDocGen ("projectBudget", "budget=".$budget_year."&proj_source=".$project_source."&user=".$userid);
			$doc->url ("Download report as document");
		?>
<br>
	</td>
</tr>

</table>
<br>
<?

if (isset($_POST['submitButton']))
{
	$sqlP = $this->getProjectList($project_source,$sec["filter"]);

	$rsP = mysqli_query($sqlP);
	if (mysqli_num_rows($rsP) > 0){
?>
		<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
<?
		$gt_planned_budget = 0;
		$gt_q1_budget = 0;
		$gt_q2_budget = 0;
		$gt_q3_budget = 0;
		$gt_q4_budget = 0;
		$gt_expenditure = 0;
		$gt_spent = 0;
		$prev_directorate = "-1";

		while($rowP = mysqli_fetch_array($rsP)){

			$registered = "Not registered in Project Register";
			$short_title = $rowP["proj_description"];
			$planned_budget = 0;
			$planned_start_date = "";
			$planned_end_date = "";

			// get Project Detail
			$sql = <<<SQL
				SELECT *
				FROM `project_detail`
 				WHERE proj_code = $rowP[proj_code]
SQL;

			$rs = mysqli_query($sql) or die(mysqli_error());
			if (mysqli_num_rows($rs) > 0){
				$prj_row = mysqli_fetch_array($rs);
				$registered = "";
				$planned_budget = $this->getBudget($budget_year,$prj_row["project_id"]);
				$planned_start_date = $prj_row["planned_start_date"];
				$planned_end_date = $prj_row["planned_end_date"];
				$short_title = $prj_row["project_short_title"];
			}

			// Display report per directorate.  Initialise totals for directorate
			if ($rowP["directorate_ref"] <> $prev_directorate) {

				/* Print totals for previous directorate */
				if ($prev_directorate != -1){
					$t_pSpent = ($t_planned_budget > 0) ? sprintf("%d", ($t_expenditure / $t_planned_budget)*100) : "&nbsp;";
					echo "<tr>";
					echo "<td>&nbsp;</td>";
					echo "<td><b>Total: ".$dir_description."</b></td>";
					echo '<td align="right"><b>'.sprintf("%01.2f",$t_planned_budget)."</b></td>";
					echo '<td align="right"><b>'. sprintf("%01.2f",$t_q1_budget) ."</b></td>";
					echo '<td align="right"><b>'. sprintf("%01.2f",$t_q2_budget) ."</b></td>";
					echo '<td align="right"><b>'. sprintf("%01.2f",$t_q3_budget) ."</b></td>";
					echo '<td align="right"><b>'. sprintf("%01.2f",$t_q4_budget) ."</b></td>";
					echo '<td align="right"><b>'.sprintf("%01.2f",$t_expenditure)."</b></td>";
					echo '<td align="right"><b>'.sprintf("%d",$t_pSpent)."</b></td>";

					echo "</tr>";

					$gt_planned_budget += $t_planned_budget;
					$gt_q1_budget += $t_q1_budget;
					$gt_q2_budget += $t_q2_budget;
					$gt_q3_budget += $t_q3_budget;
					$gt_q4_budget += $t_q4_budget;
					$gt_expenditure += $t_expenditure;
				}

				$dir_description = $this->getValueFromTable("lkp_directorate","lkp_directorate_id",$rowP["directorate_ref"],"directorate_description");

				/* initialise totals for the directorate */
				$t_planned_budget = 0;
				$t_q1_budget = 0;
				$t_q2_budget = 0;
				$t_q3_budget = 0;
				$t_q4_budget = 0;
				$t_expenditure = 0;
				$t_spent = 0;
?>

				<tr>
				<td colspan="2" class="oncolourb">Directorate: <?php echo echo $dir_description; ?></td>
				<td align="center" class="oncolourb">Budget</td>
				<td colspan="6" align="center" class="oncolourb">Expenditure</td>
				</tr>

				<tr>
				<td align=center class="oncolourb">No</td>
				<td align=center class="oncolourb">Item</td>
				<td align=center class="oncolourb">Full Year</td>
				<td align=center class="oncolourb">Ytd<br>June</td>
				<td align=center class="oncolourb">Ytd<br>Sept</td>
				<td align=center class="oncolourb">Ytd<br>Dec</td>
				<td align=center class="oncolourb">Ytd<br>Mar</td>
				<td align=center class="oncolourb">Ytd<br>Expenses</td>
				<td align=center class="oncolourb">%<br>Spent</td>
				</tr>

<?
				$prev_directorate = $rowP["directorate_ref"];
			}

			$expenditure = $this->calculateExpenditure($budget_year, $rowP["proj_code"]);

//			$qB = $this->calculateQuarterlyBudget($budget_year, $planned_budget, $planned_start_date, $planned_end_date);
			$qB = $this->calculateQuarterlyExpenditure($budget_year, $rowP["proj_code"]);

			$expenditure = ($expenditure==0) ? '&nbsp;' : sprintf("%01.2f", $expenditure);
			$planned_budget = ($planned_budget==0) ? '&nbsp;' : sprintf("%01.2f", $planned_budget);
			$qB1 = ($qB[1]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[1]);
			$qB2 = ($qB[2]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[2]);
			$qB3 = ($qB[3]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[3]);
			$qB4 = ($qB[4]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[4]);

			$pSpent = ($planned_budget > 0) ? sprintf("%d", ($expenditure / $planned_budget)*100) : "&nbsp;";

			echo "<tr>";
			echo "<td>".$rowP["proj_code"]."</td>";
			echo "<td>".$short_title."</td>";
			if ($registered == ""){
				echo '<td align="right">'.$planned_budget."</td>";
				echo '<td align="right">'. $qB1 ."</td>";
				echo '<td align="right">'. $qB2 ."</td>";
				echo '<td align="right">'. $qB3 ."</td>";
				echo '<td align="right">'. $qB4 ."</td>";
			} else {
				echo '<td align="left" colspan="5">'.$registered."</td>";
			}
			echo '<td align="right">'.$expenditure."</td>";
			echo '<td align="right">'.$pSpent."</td>";
			echo "</tr>";

			/* Accumulate totals for the directorate */
			$t_planned_budget += $planned_budget;
			$t_q1_budget += $qB1;
			$t_q2_budget += $qB2;
			$t_q3_budget += $qB3;
			$t_q4_budget += $qB4;
			$t_expenditure += $expenditure;
		}

		/* display totals for last directorate */
		$t_pSpent = ($t_planned_budget > 0) ? sprintf("%d", ($t_expenditure / $t_planned_budget)*100) : "&nbsp;";
		echo "<tr>";
		echo "<td>&nbsp;</td>";
		echo "<td><b>Total: ".$dir_description."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$t_planned_budget)."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$t_q1_budget) ."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$t_q2_budget) ."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$t_q3_budget) ."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$t_q4_budget) ."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$t_expenditure)."</b></td>";
		echo '<td align="right"><b>'.sprintf("%d",$t_pSpent)."</b></td>";
		echo "</tr>";

		$gt_planned_budget += $t_planned_budget;
		$gt_q1_budget += $t_q1_budget;
		$gt_q2_budget += $t_q2_budget;
		$gt_q3_budget += $t_q3_budget;
		$gt_q4_budget += $t_q4_budget;
		$gt_expenditure += $t_expenditure;

		$gt_pSpent = ($gt_planned_budget > 0) ? sprintf("%d", ($gt_expenditure / $gt_planned_budget)*100) : "&nbsp;";
		echo "<tr>";
		echo "<td>&nbsp;</td>";
		echo "<td><b>Total Expenses:</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$gt_planned_budget)."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$gt_q1_budget) ."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$gt_q2_budget) ."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$gt_q3_budget) ."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$gt_q4_budget) ."</b></td>";
		echo '<td align="right"><b>'. sprintf("%01.2f",$gt_expenditure)."</b></td>";
		echo '<td align="right"><b>'. sprintf("%d",$gt_pSpent)."</b></td>";
		echo "</tr>";

		echo "</td></tr></table>";

	}
	else
	{
		echo "<tr align='center'><td>No projects were found for ".$budget_year."</td></tr>";
	}


} // end if (isset($_POST['submitButton'])
?>
</td></tr>
</table>
<br>
