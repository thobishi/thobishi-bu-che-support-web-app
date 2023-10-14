<h3>Table 4.5 A Income</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;

	echo '<div class="multiGridDiv income">';
	echo '<table class="table table-hover table-bordered table-striped table-income">';
	$fieldArr = array();
		array_push($fieldArr, "type__select|name__year|description_fld__lkp_years_desc|fld_key__id|lkp_table__lkp_years|lkp_condition__1|order_by__id|default__--Select--");
		array_push($fieldArr, "type__text|name__income_subsidy");
		array_push($fieldArr, "type__text|name__income_student_fees");
		array_push($fieldArr, "type__text|name__income_other");
		array_push($fieldArr, "type__text|name__perc_total_ftes");
		array_push($fieldArr, "type__text|name__perc_total_salaries");
		$headingArr = array("YEAR", "Subsidy (in relation to FTEs)", "Student Fees", "Other (please specify)", "% of Total FTEs", "% of Total Salaries");
		$this->gridShowRowByRow("nr_programme_budget","id","nr_programme_id__".$prog_id,$fieldArr,$headingArr, 40, 1, "true", "true",1);
	echo '</table>';
	echo '</div>';

	$this->showSaveAndContinue('_label_ser_budget_expenses');
	$this->cssPrintFile('print.css');
?>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
