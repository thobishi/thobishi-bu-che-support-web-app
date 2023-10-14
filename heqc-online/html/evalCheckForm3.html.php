<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<br><br>
<center>The letter of appointment was sent out to each evaluator.</center>
<br><br>
<center><b>Indicate who of the following evaluators have accepted to take part in the programme evaluation.</b></center>
<br><br>
<table width="45%" border=0 align="center" cellpadding="2" cellspacing="2">
<?php 
$headingArray = array();
array_push($headingArray,"Evaluator");
array_push($headingArray,"Confirmed");

$refDispArray = array();
array_push($refDispArray,"Names");
array_push($refDispArray,"Surname");

$dispFields = array();
array_push($dispFields,"evalReport_status_confirm");

$ref = "";
if ($only_1_eval) {
	$ref = " AND Persnr_ref=".$only_1_eval." ";
	echo '<input type="hidden" name="eval_id" value="'.$only_1_eval.'">';
}
//$this->makeGRID("Eval_Auditors,evalReport",$refDispArray,"Persnr","Persnr_ref=Persnr".$ref." AND evalReport_status_confirm!=-1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,"evalReport","evalReport_id","Persnr_ref","application_ref",$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,$dispFields,$headingArray,"javascript:checkEvaluators();");
$this->makeGRID("Eval_Auditors,evalReport",$refDispArray,"Persnr","Persnr_ref=Persnr".$ref." AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,"evalReport","evalReport_id","Persnr_ref","application_ref",$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,$dispFields,$headingArray,"javascript:checkEvaluators();", "", "", "", "", "", "contactEvaluator");
?>
<input type="hidden" name="rec_id">
<input type="hidden" name="contact_eval">
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
</table>
<br><br>
<center><b>If all evaluators have confirmed, click "Next".</b></center>
<br><br>
</td></tr></table>
