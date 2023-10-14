<h3>Table 4.5 B Expenses</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	
	echo '<div class="multiGridDiv expenses">';
	echo '<table class="table table-hover table-bordered table-striped table-income">';
	$fieldArr = array();
		array_push($fieldArr, "type__select|name__year|description_fld__lkp_years_desc|fld_key__id|lkp_table__lkp_years|lkp_condition__1|order_by__id|default__--Select--");
		array_push($fieldArr, "type__text|name__exp_salary_academic");
		array_push($fieldArr, "type__text|name__exp_salary_support");
		array_push($fieldArr, "type__text|name__exp_salary_other");
		array_push($fieldArr, "type__text|name__exp_fixed_assets");
		array_push($fieldArr, "type__text|name__exp_supplies_services");
		array_push($fieldArr, "type__text|name__exp_other");
		$headingArr = array("YEAR", "Salaries - Academic staff", "Salaries - Support staff (incl. admin staff)", "Salaries - Other (staff contracts)", "Fixed assets", "Supplies and services","Other");
		$this->gridShowRowByRow("nr_programme_budget","id","nr_programme_id__".$prog_id,$fieldArr,$headingArr, 40, 1, "true", "true",1);
	echo '</table>';
	echo '</div>';

	$this->showSaveAndContinue('_label_ser_budget_student');
	$this->cssPrintFile('print.css');
?>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>