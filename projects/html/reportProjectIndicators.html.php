<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<b>This report provides a summary of the performance indicators for a specific budget year.</b>
<br>
<br>

<?
	$userid = $this->currentUserID;
	$sec = $this->getSecurityAccess($userid);

	$budget_year  = "";
	$budget_yearID   = (isset($_POST['budget_year']) && $_POST['budget_year'] != "") ? $_POST['budget_year'] : "";
	$budget_year  = ($budget_yearID != "") ? $this->getValueFromTable("lkp_budget_year", "lkp_budget_year_id", $_POST['budget_year'], "lkp_budget_year") : "";

	$this->formFields["budget_year"]->fieldValue = $budget_yearID;

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
	<td>

	<?
		$userid = $this->currentUserID;
		$sec = $this->getSecurityAccess($userid);

		$project_ref = (isset($_POST['project_ref']) && $_POST['project_ref'] != "") ? $_POST['project_ref'] : "";
		$directorate_ref = (isset($_POST['directorate_ref']) && $_POST['directorate_ref'] != "") ? $_POST['directorate_ref'] : "";
		$this->formFields["project_ref"]->fieldValue = $project_ref;

		$doc = new octoDocGen ("projectIndicators", "budget_year=".$budget_year."&userid=".$userid."&budget_id=".$budget_yearID);
		$doc->url ("Download Report");
	?>
	 will save the report as a rich text format document.

	</td>
</tr>
<tr align="right">
<td colspan="4"><hr></td>
</tr>

</table>
<br>
<?

if (isset($_POST['submitButton'])) {
	$this->displayProjectIndicatorReport("table", $sec, $budget_yearID, $budget_year);
}//end if

?>
