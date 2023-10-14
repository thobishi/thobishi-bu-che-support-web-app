<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>

	<br>
	<?php echo $this->displayReaccredHeader ($reaccred_id)?>
	<br>

	</td>
</tr>
<tr>
	<td align="center">
	<br>
	You have selected the following evaluators as the evaluators that have evaluated this application.  If this is correct 
	click on Next to continue.  If this is not correct then click previous and re-select the evaluators for this application.
	</td>
</tr>
<tr>
	<td align="center">
	<br>
	<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">
<?php 

	$unique_flds = "reaccreditation_application_ref__".$reaccred_id;

	$headingArray = array();
	array_push($headingArray,"Evaluator");
	array_push($headingArray,"Confirmed");

	$fieldArr = array();
	array_push($fieldArr, "type__select|name__Persnr_ref|status__3|size__100|description_fld__Names__Surname|fld_key__Persnr|lkp_table__Eval_Auditors|lkp_condition__1|order_by__Names");
	array_push($fieldArr, "type__radio|name__evalReport_status_confirm|description_fld__lkp_confirm_desc|fld_key__lkp_confirm_id|lkp_table__lkp_confirm|lkp_condition__lkp_confirm_id=1|order_by__lkp_confirm_desc");

	$ref = "";

	$this->gridShowRowbyRow("evalReport", "evalReport_id", $unique_flds, $fieldArr, $headingArray);

?>
	</table>
	</td>
</tr>

</table>
