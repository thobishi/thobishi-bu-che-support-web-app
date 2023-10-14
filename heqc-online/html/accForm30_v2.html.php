<a name="application_form_question10"></a>
<br>
<?php

	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }

	$this->displayRelevantButtons($current_id, $this->currentUserID);

	$prov_type = $this->checkAppPrivPubl($current_id);

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<b>C. PROGRAMMES OFFERED THROUGH DISTANCE EDUCATION</b>
<br>
<br>

<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>

<?php
	if ($prov_type == 2) {
		$intro =<<< INTRO
			<tr>
				<td valign="top" colspan="2">
					Please note that this section should be completed by public higher education institutions <u>not</u> classified by the DoE as distance education institutions,
					but who are applying for accreditation to offer a programme through distance education.
					<br><br>
				</td>
			</tr>
INTRO;
		echo $intro;
	}
?>

<tr>
	<td valign="top"><b>10.1</b></td><td valign="top"><b>Provide a rationale for the use of distance education for the delivery of this programme to the intended target learners.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("10_1_programmedelivery_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>10.2</b></td><td valign="top"><b>Provide evidence of the institution's systems, structures, policies, procedures and processes for materials development and delivery for distance learning.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("10_2_systemsevidence_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>10.3</b></td><td valign="top"><b>Describe quality assurance policy and procedures for monitoring teaching and learning.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("10_3_qualityassurancepolicy_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>10.4</b></td><td valign="top"><b>Indicate how staff are trained, monitored and supported for the specialised distance education roles they perform, including the design, management and delivery of the programmes.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("10_4_stafftraining_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>10.5</b></td><td valign="top"><b>Indicate how the design of the programme relates to the strategy for teaching and learning at a distance, including arrangements for students to access texts and materials required by the curriculum.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("10_5_strategy_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>10.6</b></td><td valign="top"><b>Describe in detail the policy for formative and summative assessment, including mention of feedback to students and the conduct of examinations.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("10_6_assessmentpolicy_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>10.7</b></td><td valign="top"><b>Describe mechanisms for student support. If contact sessions are offered, describe the systems in detail.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("10_7_studentsupport_text") ?><br><br></td>
</tr>
</table>

<fieldset>
<legend><b>Upload documents</b></legend>
<br>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="0">
	<tr>
		<td class="oncolour">
		<b>Any other documentation which will indicate your compliance with this criterion.</b><br>
		</td>
	</tr>
	<tr>
		<td>Upload document electronically:<?php $this->makeLink("10_additional_doc") ?><br></td>
	</tr>
</table>



</fieldset>

<br>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>


</td>
</tr>
</table>

<hr>