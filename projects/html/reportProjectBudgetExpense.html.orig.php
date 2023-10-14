
<br>
<table width="99%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

This report provides a summary of the budget and expenditure per project.
Select the budget year for which you want information extracted.
<br>
<br>


<?
	$budget_year   = (isset($_POST['budget_year']) && $_POST['budget_year'] != "") ? $_POST['budget_year'] : "";
	$month   = (isset($_POST['month']) && $_POST['month'] != "") ? $_POST['month'] : "";
	$this->formFields["budget_year"]->fieldValue = $budget_year;
	$this->formFields["month"]->fieldValue = $month;
?>

<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">

<tr align="right">
	<td width="15%">
		Budget year:
	</td>
	<td width="40%" align="left">
		<?php echo $this->showField('budget_year');	?>
	</td>
</tr>

<tr align="right">
	<td width="15%">
		Month:
	</td>
	<td width="40%" align="left">
		<?php echo $this->showField('month');	?>
	</td>
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
	$sqlP = <<<SQL
		SELECT *
		FROM `project_detail` as d
		WHERE d.budget_year = '$budget_year'
		ORDER BY directorate_ref,proj_code;
SQL;

	$rsP = mysqli_query($sqlP);
	if (mysqli_num_rows($rsP) > 0){
?>
		<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2">
<?
		$prev_directorate = "-1";
		while($rowP = mysqli_fetch_array($rsP)){

			// Display report per directorate.
			if ($rowP["directorate_ref"] <> $prev_directorate) {
				$dir_description = $this->getValueFromTable("lkp_directorate","lkp_directorate_id",$rowP["directorate_ref"],"directorate_description");
?>

				<tr>
				<td colspan="2" class="oncolourb">Directorate: <?php echo echo $dir_description; ?></td>
				<td colspan="5" align="center" class="oncolourb">Budget</td>
				<td colspan="2" align="center" class="oncolourb">Expenditure</td>
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

			$expenditure = $this->calculateExpenditure($budget_year, $month, $rowP["proj_code"]);

			$qB = $this->calculateQuarterlyBudget($budget_year, $rowP["planned_budget"],$rowP["planned_start_date"],$rowP["planned_end_date"]);

			$planned_budget = ($rowP["planned_budget"]==0) ? '&nbsp;' : sprintf("%01.2f", $rowP["planned_budget"]);
			$qB1 = ($qB[1]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[1]);
			$qB2 = ($qB[2]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[2]);
			$qB3 = ($qB[3]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[3]);
			$qB4 = ($qB[4]==0) ? '&nbsp;' : sprintf("%01.2f", $qB[4]);

			$pSpent = "&nbsp;";
			if ($rowP["planned_budget"] > 0) {
				$pSpent = sprintf("%d", ($expenditure / $rowP["planned_budget"])*100);
			}

			echo "<tr>";
			echo "<td>".$rowP["proj_code"]."</td>";
			echo "<td>".$rowP["project_short_title"]."</td>";
			echo '<td align="right">'.$planned_budget."</td>";
			echo '<td align="right">'. $qB1 ."</td>";
			echo '<td align="right">'. $qB2 ."</td>";
			echo '<td align="right">'. $qB3 ."</td>";
			echo '<td align="right">'. $qB4 ."</td>";
			echo '<td align="right">'.$expenditure."</td>";
			echo '<td align="right">'.$pSpent."</td>";
			echo "</tr>";

		}

	}
	else
	{
		echo "<tr align='center'><td>No projects were found for ".$budget_year."</td></tr>";
	}

	echo "</table>";

} // end if (isset($_POST['submitButton'])
?>
</td></tr>
</table>
<br>