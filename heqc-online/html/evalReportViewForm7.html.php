<a name="application_form_question8"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr><td>
<?php 
$SQL = "SELECT Persnr_ref FROM evalReport WHERE evalReport_id =? AND evalReport_status_confirm=1";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
$row = mysqli_fetch_array($rs);
$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Names")."&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname");
echo "<b>".$name."'s report:</b><br>";
$question = 7;
?>
</td></tr>
<tr><td>
<br>
<b>Read the report and make sure that it is complete. Use the space provided to make any comments on the quality of the report.</b>
<br><br>
<b>8. POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS &nbsp;</b>
<br><br>
Taking into account the relationship between the institution's answer to question 8 and the supporting documentation provided, indicate
<br><br>
<table><tr>
	<td valign="top"><b>8.1</b></td><td valign="top"><b>Do you think that the processes applied for the postgraduate programmes for the admission and selection of students are appropriate to a programme at a postgraduate level? </b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("8_eval_question_1") ?></td>
</tr><tr>
	<td valign="top"><b>8.2</b></td><td valign="top"><b>Do you think the method to select supervisors takes into account the quality of the student learning experience? </b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("8_eval_question_2") ?></td>
</tr><tr>
	<td valign="top"><b>8.3</b></td><td valign="top"><b>Is the definition of the roles and responsibilities of supervisors and students regulated and managed in such a way that students have some guarantee of receiving  quality  postgraduate education at the appropriate level?</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("8_eval_question_3") ?></td>
</tr></table>
<br><br>
Minimum standards<br>
The following are the minimum standards that the programme will be expected to meet to fulfill the requirements of the Accreditation Phase. Please indicate in relation to each minimum standard whether the programme: has achieved standards at a remarkable level and deserves  commendation (C); meets minimum standards (MMS); has not reached minimum standards (NRMS), or does not comply (DNC)
<br><br>
<b>Overall comment:</b><br><i>
<?php 
$this->showField('evalReport_q'.$question);
?>
</i>
<br><br>
<table width="100%" border="1"  cellpadding="2" cellspacing="2">
<tr>
<td>&nbsp;</td>
<td>C</td>
<td>MMS</td>
<td>NRMS</td>
<td>DNC</td>
</tr>
<?php 
$this->makeRelRadioTable("eval_report_questions","eval_report_id","eval_report_text","eval_report_question = ".$question."","lnk_eval_question","lnk_id","lnk_report_ref","lnk_question_ref","lnk_answer_ref",$this->dbTableInfoArray["evalReport"]->dbTableCurrentID,"4","eval_question_answer","answer_id","eval_report_sequence_nr","javascript: CalculateScore();",false);
?>
<tr>
<td>compliance:</td>
<td colspan="4" align="right" nowrap><?php echo $this->getValueFromTable("evalReport","evalReport_id",$this->dbTableInfoArray["evalReport"]->dbTableCurrentID,"evalReport_q".$question."_comp")?>%</td>
</tr>
</table>
<br><br>
</td></tr></table>
</td></tr></table>

