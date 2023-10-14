<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr><td>
<?php 
$SQL = "SELECT Persnr_ref FROM evalReport WHERE evalReport_id =? AND evalReport_status_confirm=1";
//$rs = mysqli_query($SQL);

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

$row = mysqli_fetch_array($rs);
$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Names")."&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname");
echo "<b>".$name."'s report:</b><br>";
$this->showInstitutionTableTop();
$question = 5;
?>
</td></tr>
<tr><td>
<br>
<b>Read the report and make sure that it is complete. Use the space provided to make any comments on the quality of the report.</b>
<br><br>
<b>6. VENUES AND IT INFRASTRUCTURE&nbsp;</b><br><br>
Taking into account the relationship between the institution's answer to question 6 and the supporting documentation provided, indicate:
<br><br>
<table><tr>
	<td valign="top"><b>6.1</b></td><td valign="top"><b>To what extent does the programme have suitable and sufficient lecturing venues? </b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("6_eval_question_1") ?></td>
</tr><tr>
	<td valign="top"><b>6.2</b></td><td valign="top"><b>To what extent do the IT infrastructure and library resources available for students and staff match the programme requirements? </b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("6_eval_question_2") ?></td>
</tr><tr>
	<td valign="top"><b>6.3</b></td><td valign="top"><b>Are the regulation about maintenance and management of the library appropriate to gurantee that they support students and staff? How do these regulations relate to the programme? </b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("6_eval_question_3") ?></td>
</tr><tr>
	<td valign="top"><b>6.4</b></td><td valign="top"><b>Is the staff development available for library staff sufficient?</b></td>
</tr><tr>
	<td colspan="2" valign="top"><?php $this->showField("6_eval_question_4") ?></td>
</tr></table>
<Br><br>
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
If you want to make any notes regarding this question, use this space:
<?php 
$this->showField('evalReport_comment'.$question);
?>
</td></tr></table>
</td></tr></table>


