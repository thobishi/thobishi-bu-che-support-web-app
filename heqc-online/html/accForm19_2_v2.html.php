<a name="application_form_question9"></a>
<br>
<?php 
	$site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
//echo $site_id;
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->getApplicationInfoTableTopForHEI_perSite($app_id, $site_id);

	$prov_type = HEQConline::checkAppPrivPubl($app_id);
	//set up numbering because it differs whether HEI is priv or public
	$n = 1;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

<b>9. POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS: (Criterion 9)
</b>
<br>
<br>

<fieldset>
<legend>Minimum standards</legend>
Postgraduate programmes have appropriate policies, procedures and regulations for the admission and selection of students; the selection and appointment of supervisors; and the definition of the roles and responsibilities of supervisors and students, etc.</fieldset>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<?php

	if ($prov_type == 1) {
?>
		<tr>
			<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>Provide a rationale for offering postgraduate programmes (where applicable provide details of track record in terms of research/scholarly output):</b></td>
		</tr>
		<tr>
			<td>&nbsp;</td><td valign="top"><?php $this->showField("9_1_rational_text") ?><br><br></td>
		</tr>
<?php 
	}
?>

<tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>Provide a description of the process for approval of student research proposals and completed dissertations/theses:</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_2_process_text") ?><br><br></td>
</tr><tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>Outline the criteria for the selection and appointment of supervisors:</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_3_supervisorcriteria_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>How is supervision built into workload models?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_4_supervision_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>Summarise the guidelines governing the roles and responsibilities of students and supervisors. Attach all policies and procedures in relation to supervision (in "Documentation" section, below).</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_5_rolesresponsibilites_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>Describe policies and procedures in place to deal with student complaints, grievances, plagiarism, re-marking, etc.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_6_complaints_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>Detail the assessment procedures for long essays, dissertations and theses.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_7_assessmentprocedures_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>Existing postgraduate institutions:
	<ul>
		<li>Discuss staff development practices undertaken over the last 3 years in relation to postgraduate supervision.</li>
		<li>Expenditure on research for the past 3 years</li>
		<li>Research/scholarly output for the past 3 years</li>
	</ul>
	</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_8_last3years_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>What plans are in place to mentor academic staff into research activities? </b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_9_staffmentoring_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>Provide a description of how the programme enables students to undertake independent research and other scholarly activities.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_10_independentresearch_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>9.<?php echo $n++?></b></td><td valign="top"><b>Provide a budget for research:</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_11_researchbudget_text") ?><br><br></td>
</tr>
</table>
<br><br>

<fieldset>
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>
<br>
<?php
/*
<!-- The following is for PRIVATE providers  -->
*/
//hardcoded - take out
$display2 = "block";
?>
<div style="display:<?php echo $display2?>">
	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Research policy:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("9_researchpolicy_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Policies/procedures for the appointment of supervisors:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("9_supervisorappointment_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Code of Ethics:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("9_codeethics_doc") ?></td>
				</tr>
			</table>
		</li>
	</ul>
</div>
<?php
/*
<!-- The following is for PUBLIC AND PRIVATE providers  -->
*/ ?>
	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour">
					<b>Any other documentation which will indicate your compliance with this criterion.</b><br>
					</td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("9_additional_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>



</fieldset>

</td></tr></table>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>

<hr>