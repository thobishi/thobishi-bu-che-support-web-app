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
	The letter of appointment was sent out to the selected evaluators.
	</td>
</tr>
<tr>
	<td align="center">
	<br>
	<center><b>Indicate who of the following evaluators have accepted to take part in the programme evaluation:</b></center>


	<table width="70%" border=0 align="center" cellpadding="2" cellspacing="2">
<?php 


	$unique_flds = "reaccreditation_application_ref__".$reaccred_id;

	$headingArray = array();
	array_push($headingArray,"Evaluator");
	array_push($headingArray,"Confirmed");

	$fieldArr = array();
	array_push($fieldArr, "type__select|name__Persnr_ref|status__3|size__100|description_fld__Names__Surname|fld_key__Persnr|lkp_table__Eval_Auditors|lkp_condition__1|order_by__Names");
	array_push($fieldArr, "type__radio|name__evalReport_status_confirm|description_fld__lkp_confirm_desc|fld_key__lkp_confirm_id|lkp_table__lkp_confirm|lkp_condition__1|order_by__lkp_confirm_desc");

	$ref = "";

	$this->gridShowRowbyRow("evalReport", "evalReport_id", $unique_flds, $fieldArr, $headingArray);

//$this->makeGRID(,$refDispArray,"Persnr",,,$dispFields,$headingArray,"javascript:checkEvaluators();", "", "", "", "", "", "contactEvaluator");

?>
	<input type="hidden" name="rec_id">
	<input type="hidden" name="contact_eval">

	</table>
	</td>
</tr>

<tr>
	<td align="center">
	<br>
	Evaluators that have accepted to take part in the programme evaluation will have access to the programme until: <?php $this->showfield('evaluator_access_end_date'); ?>
	<br><br>
	</td>
</tr>

<tr>
	<td align="center">
	<br>
	<b>If all evaluators have confirmed, click "Next".</b>
	<br><br>
	</td>
</tr>

</table>
<script>
function makeContact (id) {
	document.defaultFrm.contact_eval.value=id;
}

function checkEvaluators(){
	var obj = document.defaultFrm;
	var docArrChecked = new Array();
	var docArr = new Array();
	var docArrCount = 0;
	var docArrCheckedCount = 0;
	for (i=0; i<obj.elements.length; i++) {
		if ((obj.elements[i].type == "radio") && obj.elements[i].checked) {
			docArr[docArrCount] = obj.elements[i];
			docArrCount++;
			if (obj.elements[i].value == 1) {
				docArrChecked[docArrCheckedCount]	= obj.elements[i];
				docArrCheckedCount++;
			}
		}
	}
	if (docArrChecked.length/docArr.length == 1 ) {
		showHideAction("next", true);
	}else {
		showHideAction("next", false);
	}
}
checkEvaluators();
</script>