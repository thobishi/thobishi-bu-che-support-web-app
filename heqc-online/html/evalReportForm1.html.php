<?php 
//functions to print offline version.
//$tmpSettings = "DBINF_Institutions_application___application_id=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."&DBINF_evalReport___evalReport_id=6";
//echo "<a href='javascript:printOfflineEvalForm(\"Evaluation\",\"6\",\"".base64_encode($tmpSettings)."\",\"\");'>View Report</a>";
?>
<?php 
$this->showInstitutionTableTop();
$question = 1;
$SQL = "SELECT count(*) FROM eval_report_questions WHERE eval_report_question = ".$question;
$rs = mysqli_query($SQL);
$row = mysqli_fetch_array($rs);
$count = $row[0];
?>

<script>
var choices = new Array();
var total = 0;

<?php 
$SQL = "SELECT answer_count FROM eval_question_answer ORDER BY answer_id";
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
$array_id = 0;
while ($row = mysqli_fetch_array($rs)){
	if ($row[0] != "n/a") {
		echo "choices[".$array_id."] = ".$row[0].";";
	}
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
		score = Math.round(tmpScore*100/total);
		//this could also work:  if (parseInt(score) != (score-0)){}
		if (isNaN(parseInt(score))) {
			document.defaultFrm.FLD_evalReport_q<?php echo $question?>_comp.value = 'n/a';
		}else {
			document.defaultFrm.FLD_evalReport_q<?php echo $question?>_comp.value = score;
		}
	}else{
		document.defaultFrm.FLD_evalReport_q<?php echo $question?>_comp.value = "";
	}
}
</script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table><tr>
	<td>
	<b>Instructions:</b>
		<ul>
			<li>Write in a narrative style, evaluating the learning programme in relation the minimum standards specified for each criterion statement.</li>
			<li>If you have covered certain aspects under other sections, please ensure that there is appropriate cross-referencing.</li>
			<li>At the end of each criterion statement, please tick off the minimum standards in terms of the evaluation categories.</li>
			<li>Provide a holistic evaluation of the performance of the institution in relation to the overall criterion.</li>
			<li>At the end of the evaluation, make your recommendation in terms of accreditation.</li>
		</ul>
</td>
</tr></table>
<table width="75%" border=0  cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>1. PROGRAMME DESIGN&nbsp;</b>
<br><br>
<table><tr>
	<td valign="top"><b>1.1</b></td><td valign="top"><b>Evaluate the extent to which the programme is aligned to the institution's mission and goals and if the programme is integrated into the planning and resource allocation processes of the institution.</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("1_eval_question_1") ?></td>
</tr><tr>
	<td valign="top"><b>1.2</b></td><td valign="top"><b>Evaluate the congruency between the qualification structure (SAQA submission) and the programme design and curriculum. Comment on the articulation possibilities with other programmes.</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("1_eval_question_2") ?></td>
</tr><tr>
	<td valign="top"><b>1.3</b></td><td valign="top"><b>Comment on the appropriacy of the NQF level, qualification designation, sequencing of modules and categorization of modules as fundamental, core and electives. Your evaluation should take into account minimum standards ii, iii, iv, v and vi.</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("1_eval_question_3") ?></td>
</tr><tr>
	<td valign="top"><b>1.4</b></td><td valign="top"><b>In relation to the table of learning activities, please evaluate the adequacy of the hours allocated to teaching and the types of learning activities planned. Please note that your evaluation should take into account the specified credits, % of learning activities and the mode of delivery specified by the institution.</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("1_eval_question_4") ?></td>
</tr><tr>
	<td valign="top"><b>1.5</b></td><td valign="top"><b>In the case of professional/vocational programmes, evaluate the extent to which the design of the programme takes into account the requirements of the profession or occupation.</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("1_eval_question_5") ?></td>
</tr><tr>
	<td valign="top"><b>1.6</b></td><td valign="top"><b>In the case of service learning, what is the extent to which the learning programmes are integrated into institutional and academic planning and the required mechanism, structures, systems are in place.</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("1_eval_question_6") ?></td>
</tr><tr>
	<td valign="top"><b>1.7</b></td><td valign="top"><b>Evaluate the teaching and learning policy of the institution.</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("1_eval_question_7") ?></td>
</tr><tr>
	<td valign="top"><b>1.8</b></td><td valign="top"><b>If the institution has provided any other policies relevant to this criterion, please comment on these in relation to the overall criterion statement.</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("1_eval_question_8") ?></td>
</tr></table>
<br><br>
<b>Minimum standards</b><br>
The following are the minimum standards that the programme will be expected to meet to fulfill the requirements of the Accreditation Phase. Please indicate in relation to each minimum standard whether the programme: has achieved standards at a remarkable level and deserves  commendation (C); meets minimum standards (MMS); has not reached minimum standards (NRMS), or does not comply (DNC)
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
$this->makeRelRadioTable("eval_report_questions","eval_report_id","eval_report_text","eval_report_question = ".$question." and eval_report_section = 1","lnk_eval_question","lnk_id","lnk_report_ref","lnk_question_ref","lnk_answer_ref",$this->dbTableInfoArray["evalReport"]->dbTableCurrentID,"-1","eval_question_answer","answer_id","eval_report_sequence_nr","javascript: CalculateScore();");
?>
<tr>
<td align="right"><b>Compliance:</b> </td>
<td colspan='4' align="center"><?php echo $this->showField('evalReport_q'.$question.'_comp')?>%</td>
</tr>
</table>
<br><br>

<b>Please make an overall comment on the design of the proposed programme.</b><br>
<?php 
$this->showField('evalReport_q'.$question);
if ($this->formFields["evalReport_comment".$question]->fieldValue > ""){
	echo "<br><br>Managers Comments:<br><i>";
	$this->showField('evalReport_comment'.$question);
	echo "</i>";
}

?>
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
<?php $this->createEvalActions($question);?>
