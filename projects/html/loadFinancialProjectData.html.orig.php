<br>
<table width="99%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr><td>

<?
		$budget_year = '2007/2008';
	//	$budget_year = $this->createBudgetYear();
?>

		<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
			<tr>
				<td width="80%">
					Financial project data will be loaded for <?php echo echo $budget_year; ?>.
				</td>
			</tr>
			<tr>
				<td width="80%">
					The existing financial data for this budget year will be archived.
				</td>
			</tr>

			<tr align="center">
				<td>
					<input type="submit" class="btn" name="submitButton" value="Load" onClick="moveto('stay');">
				</td>
			</tr>

		</table>

		<br>

<?
		if (isset($_POST['submitButton']))
		{
			$archive_rows = $this->archiveFinancialProjectData($budget_year);
			echo "<p><b>Number of records archived for $budget_year:</b> <i>".$archive_rows."</i>";
			$loaded_rows  = $this->loadFinancialProjectData($budget_year);
			echo "<p><b>Number of records loaded for $budget_year:</b> ".$loaded_rows;
		//	echo $this->displayFinancialProjectData($budget_year);

		} // end if (isset($_POST['submitButton'])
?>
	</td></tr>
</table>
<br>