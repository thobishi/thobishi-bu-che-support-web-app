
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<b>This report provides a summary of the performance indicators for a specific budget year.</b>
<br>
<br>

<?
	$userid = $this->currentUserID;
	$sec = $this->getSecurityAccess($userid);

	$budget_year   = (isset($_POST['budget_year']) && $_POST['budget_year'] != "") ? $this->getValueFromTable("lkp_budget_year", "lkp_budget_year_id", $_POST['budget_year'], "lkp_budget_year") : "";
	$this->formFields["budget_year"]->fieldValue = $budget_year;

?>
Select the budget year for which you want information extracted. The outcomes per year that are captured quarterly per project will display.
<br>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr align="left">
	<td width="15%" align="right">
		Budget year:
	</td>
	<td colspan="2">
		<?php echo $this->showField('budget_year');	?>
	</td>
</tr>
<tr align="left">
	<td>&nbsp;</td>
	<td>
		<input type="submit" class="btn" name="submitButton" value="Create report" onClick="moveto('stay');">
	</td>
	<td>&nbsp;
	</td>
</tr>
<tr align="right">
<td colspan="4"><hr></td>
</tr>

</table>
<br>
<?

if (isset($_POST['submitButton']))
	{
		$sql = <<<SQL
			SELECT *
			FROM project_detail_per_year AS y
			LEFT JOIN project_detail AS d
			ON y.project_ref = d.project_id
SQL;

	$whereArr = array(1);

	// Users are restricted as to which projects they may see
	if ($sec["filter"] > ""){
		array_push ($whereArr, $sec["filter"]);
	}

	if ($budget_year > ""){
		array_push($whereArr,"y.budget_year = '".$budget_year."'");
	}

	$where = " WHERE " . implode(" AND ", $whereArr);

	$sql .= $where;

	$rs = mysqli_query($sql);
	if (mysqli_num_rows($rs) > 0)
	{
?>
		<table width="100%" border="1" align="center" cellpadding="2" cellspacing="0">

		<tr class="onblueb" valign="top" align="center">
			<td width="5%">Project Code</td>
			<td width="15%">Title</td>
			<td width="5%">Budget Year(s)</td>
			<td>Capacity Development</td>
			<td>Stakeholder Feedback</td>
			<td>Outputs/Deliverables</td>
			<td width="10%">Planned Budget</td>
			<td width="10%">Expenditure Figure</td>
			<td width="4%">% Spent</td>
			<!--td>Comments</td-->
		<tr>
<?
		while ($row = mysqli_fetch_array($rs))
		{
?>
		<tr>
			<?
				$expenditure = $this->calculateExpenditure($budget_year, $row["proj_code"]);
				$planned_budget = $this->getBudget($budget_year,$row["project_id"]);
				$pSpent = ($planned_budget > 0) ? sprintf("%d", ($expenditure / $planned_budget)*100) : "0;";

				echo '<td>'.$row["proj_code"].'</td>';
				echo '<td>'.$row["project_short_title"].'</td>';
				echo '<td>'.$row["budget_year"].'</td>';
				echo '<td>'.$row["capacity_development"].'</td>';
				echo '<td>'.$row["stakeholder_feedback"].'</td>';
				echo '<td>'.$row["outputs_deliverables"].'</td>';
				echo '<td align="right">R '.sprintf("%01.2f", $planned_budget).'</td>';
				echo '<td align="right">R '.sprintf("%01.2f", $expenditure).'</td>';
				echo '<td align="center">'.$pSpent.'</td>';
			?>
		</tr>
<?
		}//end while
?>
		</table>
<?
	}//end if
	else
	{
		echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"><tr align="center"><td>No data is available for your selection.</td><tr></table>';
	}//end else
}//end if
?>
<br>
</td></tr>
</table>
