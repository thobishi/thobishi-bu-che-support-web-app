<?php 
$this->showInstitutionTableTop();
$question = 2;
$SQL = "SELECT count(*) FROM eval_report_questions WHERE eval_report_question = ?";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $question);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
$row = mysqli_fetch_array($rs);
$count = $row[0];
?>

<script>
var choices = new Array();
var total = 0;

<?php 
$SQL = "SELECT answer_count FROM eval_question_answer ORDER BY answer_count DESC";
$rs = mysqli_query($conn, $SQL);
$array_id = 0;
while ($row = mysqli_fetch_array($rs)){
	echo "choices[".$array_id."] = ".$row[0].";";
	$array_id++;
}
?>
	total = choices[0]*<?php echo $count?>;

function CalculateScore(){
	var tmp=0;
	for(i=0; i<document.defaultFrm.elements.length;i++){
		if (document.defaultFrm.elements[i].checked){
			tmp = tmp + 1;
		}	
	}
	if (<?php echo $count?>	 == tmp){
		var tmpScore=0;
		for(i=0; i<document.defaultFrm.elements.length;i++){
			if (document.defaultFrm.elements[i].checked){
				tmpScore = tmpScore + choices[document.defaultFrm.elements[i].value -1];
			}	
		}
		document.defaultFrm.FLD_evalReport_q<?php echo $question?>_comp.value = Math.round(tmpScore*100/total);
	}else{
	document.defaultFrm.FLD_evalReport_q<?php echo $question?>_comp.value = "";
	}
}
</script>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<table width="75%" border=0  cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>2. STUDENT RECRUITMENT, ADMISSION AND SELECTION&nbsp;</b>
<br><br>
Taking into account the relationship between the institution's answer to question 2 and the supporting documentation provided, indicate:
<br><br>
<table><tr>
	<td valign="top"><b>2.1</b></td><td valign="top"><b>To what extent is the information on the programme requirements  that the institution plans to provide  sufficient for students to make choices?</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php echo nl2br($this->getValueFromTable("evalSiteVisitReport", "evalSiteVisitReport_id", $this->dbTableInfoArray["evalSiteVisitReport"]->dbTableCurrentID, "2_eval_question_1")) ?></td>
</tr><tr>
	<td valign="top"><b>2.2</b></td><td valign="top"><b>Are the admission requirements appropriate? To what extent do they relate to the requirements for the academic level of the programme?</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php echo nl2br($this->getValueFromTable("evalSiteVisitReport", "evalSiteVisitReport_id", $this->dbTableInfoArray["evalSiteVisitReport"]->dbTableCurrentID, "2_eval_question_2")) ?></td>
</tr><tr>
	<td valign="top"><b>2.3</b></td><td valign="top"><b>To what extent  does the selection process take into account the optimal number of students to achieve the proposed learning outcomes?</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php echo nl2br($this->getValueFromTable("evalSiteVisitReport", "evalSiteVisitReport_id", $this->dbTableInfoArray["evalSiteVisitReport"]->dbTableCurrentID, "2_eval_question_3")) ?></td>
</tr><tr>
	<td valign="top"><b>2.4</b></td><td valign="top"><b>To what extend do the programme's  recruitment  and admission policies take into account the objective of widening access to higher education?</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php echo nl2br($this->getValueFromTable("evalSiteVisitReport", "evalSiteVisitReport_id", $this->dbTableInfoArray["evalSiteVisitReport"]->dbTableCurrentID, "2_eval_question_4")) ?></td>
</tr></table>
<br><br>
Minimum standards<br>
The following are the Minimum standards that the programme will be expected to meet to fulfill the requirements of the 
Accreditation Phase. Please indicate in relation to each minimum standard whether the programme: has achieved standards at 
a remarkable level and deserves  commendation (C); meets Minimum standards (MMS); has not reached Minimum standards (NRMS), 
or does not comply (DNC)
<br><br>
Please make an overall comment on the design of the proposed programme.<br>
<?php 
echo nl2br($this->getValueFromTable("evalSiteVisitReport", "evalSiteVisitReport_id", $this->dbTableInfoArray["evalSiteVisitReport"]->dbTableCurrentID, "evalReport_q".$question));
//if ($this->formFields["evalReport_comment".$question]->fieldValue > ""){
//	echo "<br><br>Managers Comments:<br><i>";
//	$this->showField('evalReport_comment'.$question);
//	echo "</i>";
//}

?>
<br><br>
<table width="100%" border="1"  cellpadding="2" cellspacing="2">
<tr>
<td>&nbsp;</td>
<td class="oncolourb" align="center">C</td>
<td class="oncolourb" align="center">MMS</td>
<td class="oncolourb" align="center">NRMS</td>
<td class="oncolourb" align="center">DNC</td>
</tr>
<?php 
$this->makeRelRadioTable("eval_report_questions","eval_report_id","eval_report_text","eval_report_question = ".$question." and eval_report_section = 1","lnk_sitevisit_report_question","lnk_id","lnk_report_ref","lnk_question_ref","lnk_answer_ref",$this->dbTableInfoArray["siteVisit"]->dbTableCurrentID,"-1","eval_question_answer","answer_id","eval_report_sequence_nr","", false);
?>
<tr>
<td align="right"><b>Compliance:</b> </td>
<td colspan='4' align="center"><?php echo nl2br($this->getValueFromTable("evalSiteVisitReport", "evalSiteVisitReport_id", $this->dbTableInfoArray["evalSiteVisitReport"]->dbTableCurrentID, "evalReport_q".$question."_comp")) ?>%</td>
</tr>
</table>
<br><br>

</td></tr></table>
<br><br>
</td></tr></table>
<script>
//CalculateScore();
function doUndisabled(){
	if (document.all.MOVETO.value == "next"){
		var tmpScore=0;
		for(i=0; i<document.defaultFrm.elements.length;i++){
			if (document.defaultFrm.elements[i].checked){
				tmpScore = tmpScore + 1;
			}	
		}
		if (<?php echo $count?>	 == tmpScore){
			document.defaultFrm.FLD_evalReport_q<?php echo $question?>_comp.disabled = false;	
			return true;
		}else{
			document.all.MOVETO.value = "";
			alert("Please answer all the questions.")
			return false
		}
	}else{
		document.defaultFrm.FLD_evalReport_q<?php echo $question?>_comp.disabled = false;	
		return true;
	}
}
</script>
<?php $this->createEvalActions($question);?>
