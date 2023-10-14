<?php 
$this->showInstitutionTableTop();
$question = 6;
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
<b>7. PROGRAMME ADMINISTRATIVE SERVICES&nbsp;</b>  
<br><br>
Taking into account the relationship between the institution's answer to question 7 and the supporting documentation provided, indicate
<br><br>
<table><tr>
	<td valign="top"><b>7.1</b></td><td valign="top"><b>What is your opinion of the programme administrative service? To what extent do you think it can provide information, manage the programme information system, and deal with a diverse student population adequately? </b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("7_eval_question_1") ?></td>
</tr><tr>
	<td valign="top"><b>7.2</b></td><td valign="top"><b>To what extent you think that the administrative services ensure the integrity of the processes leading to certification of the qualification obtained through the programme?</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("7_eval_question_2") ?></td>
</tr></table>
<br><br>
Minimum standards<br>
The following are the minimum standards that the programme will be expected to meet to fulfill the requirements of the Accreditation Phase. Please indicate in relation to each minimum standard whether the programme: has achieved standards at a remarkable level and deserves  commendation (C); meets minimum standards (MMS); has not reached minimum standards (NRMS), or does not comply (DNC)
<br><br>
Please make an overall comment on the design of the proposed programme.<br>
<?php 
$this->showField('evalReport_q'.$question);
if ($this->formFields["evalReport_comment".$question]->fieldValue > ""){
	echo "<br><br>Managers Comments:<br><i>";
	$this->showField('evalReport_comment'.$question);
	echo "</i>";
}

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
$this->makeRelRadioTable("eval_report_questions","eval_report_id","eval_report_text","eval_report_question = ".$question." and eval_report_section = 1","lnk_sitevisit_report_question","lnk_id","lnk_report_ref","lnk_question_ref","lnk_answer_ref",$this->dbTableInfoArray["siteVisit"]->dbTableCurrentID,"-1","eval_question_answer","answer_id","eval_report_sequence_nr","javascript: CalculateScore();");
?>
<tr>
<td align="right"><b>Compliance:</b> </td>
<td colspan='4' align="center"><?php echo $this->showField('evalReport_q'.$question.'_comp')?>%</td>
</tr>
</table>
<br><br>
</td></tr></table>
<br><br>
</td></tr></table>
<script>
CalculateScore();
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
<?php $this->createEvalActions(8);?>
