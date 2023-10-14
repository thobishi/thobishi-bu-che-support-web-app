<a name="application_form_question6"></a>
<br>
<?php

	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }

	$this->displayRelevantButtons($current_id, $this->currentUserID);

	$prov_type = $this->checkAppPrivPubl($current_id);

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<b>6. ASSESSMENT: (Criterion 6)</b>
<br>
<br>

<fieldset>
<legend>Minimum standards</legend>
<?php echo $this->getTextContent("accForm14_v2", "minimumStandards"); ?>
</fieldset>
<br><br>

<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>6.1</b></td><td valign="top"><b>Describe the assessment policy of the institution in relation to the programme, covering the following areas:
	<ul>
		<li>Description of the number and types of tests / assignments / projects / case studies</li>
		<li>Formative and summative assessment</li>
		<li>Internal and external moderation / examination</li>
		<li>Assessment of experiential learning (if applicable)</li>
	</ul>
	</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_1_assessmentpolicy_text") ?><br><br></td>
</tr><tr>
	<td valign="top"><b>6.2</b></td><td valign="top"><b>Describe processes to provide feedback to students on assessment tasks.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_2_assessmentfeedback_text") ?><br><br></td>
</tr>

<?php if ($prov_type == 1)
	{
?>
<tr>
	<td valign="top"><b>6.3</b></td><td valign="top"><b>What mechanisms does the institution have to ensure the explicitness, validity and reliability of assessment practices?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_3_assessmentmechanisms_text") ?><br><br></td>
</tr><tr>
	<td valign="top"><b>6.4</b></td><td valign="top"><b>What measures are in place to promote staff competence in assessment practices?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_4_staffcompetencemeasures_text") ?><br><br></td>
</tr><tr>
	<td valign="top"><b>6.5</b></td><td valign="top"><b>Describe the process for recording assessment results and settling disputes in relation to assessment results.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_5_assessmentresults_text") ?><br><br></td>
</tr>
<?php
	}
?>
</table>

<br><br>

<fieldset >
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>
<br>

		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td>
			<ul>
				<li><b>Experiential learning assessment and monitoring policy:</b>
				<br><?php $this->makeLink("6_monitoring_policy_doc") ?><br></li>

				<li><b>The following documents are required. Please zip documents and upload electronically.</b>
				<ul>
					<li>The unit's policy on assessment and examinations as applicable per module or programme</li>
					<li>Documents describing the policy for student assessment, including internal assessment; external moderation / examination; student progress; validity and reliability of assessment; grievance procedures; supplementary examinations and recording of results and security</li>
					<li>External examiner systems; mark schedules; internal moderation systems: rules and regulations pertaining to the award of the qualification.</li>
				</ul>
				<?php $this->makeLink("6_documentaryevidence_doc") ?></li>
			</ul>

			<ul>
				<li><b>Upload any other documentation which will indicate your compliance with this criterion.</b>
				<br>
				<?php $this->makeLink("6_additional_doc") ?></li>
			</ul>
			</td>
		</tr>
		</table>

		<br>
</fieldset>

<br><br>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>

</td>
</tr>
</table>

<hr>