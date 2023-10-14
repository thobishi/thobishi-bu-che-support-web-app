<a name="application_form_question6"></a>
<br>
<?php
	$site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->getApplicationInfoTableTopForHEI_perSite($app_id, $site_id)
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

<b>6. ASSESSMENT: (Criterion 6)</b>
<br>
<br>

<fieldset>
<legend>Minimum standards</legend>
The different modes of delivery of the programme have appropriate policies and procedures for internal assessment; internal and external moderation; monitoring of student progress; explictness, validity and reliability of assessment practices; recording of assessment results; settling of disputes; the rigour and security of the assessment system; RPL; and for the development of staff competence in assessment.
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
</tr><tr>
	<td valign="top"><b>6.3</b></td><td valign="top"><b>What mechanisms does the institution hav to ensure the explicitness, validity and reliability of assessment practices?</b></td>
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
</table>
<br><br>
</td>
</tr>
</table>
