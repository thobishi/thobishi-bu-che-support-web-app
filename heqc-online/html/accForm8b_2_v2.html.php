<a name="application_form_question4"></a>
<br>
<?php
	$site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->getApplicationInfoTableTopForHEI_perSite($app_id, $site_id);

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<b>4. STAFF SIZE AND SENIORITY: (Criterion 4)</b>
<br>
<br>
Taking into account the required minimum standards for the accreditation criterion on staffing, the tables of evidence and the documentation provided, please answer all aspects of question number 4.
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'><tr>
	<td valign="top"><b>4.1</b></td><td valign="top"><b>What mechanisms does the institution have to ensure that the recruitment of staff follows relevant labour legislation?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("4_1_recruitmentmechanism_text") ?><br><br></td>
</tr><tr>
	<td valign="top"><b>4.2</b></td><td valign="top"><b>Provide information on support staff for the programme, especially those with specialist technical support skills (e.g. laboratory skills, distance education support skills).</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("4_2_supportstaffinfo_text") ?></td>
</tr><tr>
	<td valign="top"><b>4.3</b></td><td valign="top"><b>What measures are in place to ensure that administrative/support staff are sufficient for the programme?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("4_3_staffmeasures_text") ?></td>
</tr></table>
<br><br>


<fieldset >
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>
<?php
	$prov_type = HEQConline::checkAppPrivPubl($app_id);


	$display1 = "none";
	$display2 = "none";
	if ($prov_type == 1) {
		$display1 = "Block";
	}
	if ($prov_type == 2) {
		$display2 = "Block";
	}

/*
<!-- The following is for private providers  -->
*/
?>
<div style="display:<?php echo $display1?>">
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Conditions of service:</b></td>
		</tr>
		<tr>
			<td>
				Upload electronically:<?php $this->makeLink("4_conditionsofservice_doc") ?>
				<br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Staff recruitment policy:</b></td>
		</tr>
		<tr>
			<td>
				Upload electronically:<?php $this->makeLink("4_staffrecruitmentpolicy_doc") ?>
				<br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Example of contracts with academic staff:</b></td>
		</tr>
		<tr>
			<td>
				Upload electronically:<?php $this->makeLink("4_academicstaffcontracts_doc") ?>
				<br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Staff equity policy:</b></td>
		</tr>
		<tr>
			<td>
				Upload electronically:<?php $this->makeLink("4_staffequitypolicy_doc") ?>
				<br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Attach implementation plans if applicable:</b></td>
		</tr>
		<tr>
			<td>
				Upload electronically:<?php $this->makeLink("4_implementationplans_doc") ?>
				<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>
</div>
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Upload any other documentation which will indicate your compliance with this criterion.</b><br></td>
		</tr>
		<tr>
			<td>
			Upload electronically:<?php $this->makeLink("4_additional_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>
</fieldset>


<br><br>
</td></tr></table>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>
<hr>