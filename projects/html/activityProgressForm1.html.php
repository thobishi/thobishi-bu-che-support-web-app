<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>

<table width="100%" border=1 align="left" cellpadding="2" cellspacing="2">
<tr><td>
	<table width="90%" border=0 align="left" cellpadding="2" cellspacing="2">

	<tr>
		<th colspan="2" align="left">
			<br>
			Activity Outcomes: Capacity Development, Stakeholder Feedback and Outputs.
			<hr>
		</th>
	</tr>
	<tr>
		<td colspan="2">Please capture the outcomes for the displayed budget year and click Save.</td>
	</tr>
	<tr>
		<td align="right" width="20%">Activity short title:</td>
		<td class="oncolourb">
		<?
			$this->formFields['project_short_title']->fieldStatus = 3;
			$this->showField("project_short_title");
		?>
		</td>
	</tr>
	<tr>
		<td align="right">Activity full title:</td>
		<td class="oncolourb">
		<?php echo  $this->formFields['project_full_title']->fieldStatus = 3;
			$this->showField("project_full_title"); ?>
		</td>
	</tr>


	<tr>
		<td>&nbsp;</td>
		<td>
		<?
			$headArr = array();
			array_push($headArr, "Budget Year");
			array_push($headArr, "Capacity Development");
			array_push($headArr, "Stakeholder feedback");
			array_push($headArr, "Outputs / Deliverables");

			$fieldArr = array();
			array_push($fieldArr, "type__select|name__budget_year|description_fld__lkp_budget_year|fld_key__lkp_budget_year|lkp_table__lkp_budget_year|lkp_condition__1|order_by__lkp_budget_year");
			array_push($fieldArr, "type__textarea|name__capacity_development|size__20");
			array_push($fieldArr, "type__textarea|name__stakeholder_feedback|size__20");
			array_push($fieldArr, "type__textarea|name__outputs_deliverables|size__20");

			$this->gridShowTableByRow("project_detail_per_year", "project_detail_per_year_id", "project_ref__".$this->dbTableInfoArray["project_detail"]->dbTableCurrentID, $fieldArr, $headArr, 4, 2);
		?>
		</td>
	</tr>
	</table>

	<br>
	<br>
	</td>
	</tr>
</table>

<?
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}
?>



<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
