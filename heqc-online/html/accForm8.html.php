<a name="application_form_question3"></a>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>3. STAFF  QUALIFICATIONS: (Criterion 3)</b> [<?php $this->popupContent("Help", "MainHelp", "", true) ?>]<br>
<br>
Taking into account the required minimum standards for the accreditation criterion on staffing, the tables of evidence and the documentation provided, please answer all aspects of question number 3.
<br><br>
<b>Minimum standards:</b> [<?php $this->popupContent("Minimum standards", "MinHelp", "", true) ?>]<br>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>3.1</b></td><td valign="top"><b>Indicate how the qualification and expertise of the academic staff responsible for the programme are sufficient and relevant for the level and focus of the programme.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("3_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>3.2</b></td><td valign="top"><b>What kind of teaching and assessment competence does the academic staff attached to the programme have?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("3_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>3.3</b></td><td valign="top"><b>How does the research profile of the academic staff  match the  nature and level of the programme? </b> </td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("3_3_comment") ?></td>
</tr><tr>
	<td valign="top"><b>3.4</b></td><td valign="top"><b>What opportunities does the institution provide for academic staff to enhance their competences and to support their professional growth and development?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("3_4_comment") ?></td>
</tr><tr>
	<td valign="top"><b>3.5</b></td><td valign="top"><b>Provide a detailed description of your workload allocation model taking into account the number of academic staff attached to the programme and envisaged student enrolments.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("3_5_comment") ?></td>
</tr></table>
<br><br>

<?php /*
<br><br>

<div id="notComply" style="display:none">
	<b>*Please suggest improvement:</b>
</div>
<div id="comply" style="display:Block">
<b>Taking into account the required minimum standards, please answer all aspects of question number 3:</b>
</div>
<?php//$this->showField("3_comment") ?>
<br><br>

<b>Please tick in the box the extent to which the proposed programme meets the required minimum standards for this criterion:</b><br>
<?php//$this->showField("3_criteria") ?>
<br><br>
*/ ?>
<b>In the space below indicate to what extent does your programme comply with the criterion 3:</b><br>
<?php $this->showField("3_criteria") ?>
<br><br>
<?php /*
<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation:</b>
<?php//$this->showField("3_self_evaluation") ?>
<br><br>
*/ ?>

<fieldset >
<legend><b>Required Documentation</b></legend>
<br>
<?php
	$prov_type = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "institution_ref"), "priv_publ");
	$display1 = "none";
	$display2 = "none";
	if ($prov_type == 1) {
		$display1 = "Block";
	}
	if ($prov_type == 2) {
		$display2 = "Block";
	}
?>

<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
<tr>
<td><?php $this->showInstProfileUploadedDocs($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "institution_id"));?></td>
</tr>
</table>
<br><br>
<?php/*
<!-- The following is for private providers  -->
*/ ?>
<div style="display:<?php echo $display1?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPrivate()?>
</td></tr></table>
</div>
<?php/*
<!-- The following is for PUBLIC providers  -->
*/ ?>
<div style="display:<?php echo $display2?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPublic()?>
</td></tr></table>
</div>

<br><br>
<?php/*
<!-- The following is for private providers  -->
*/ ?>
<div style="display:<?php echo $display1?>">
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Staff development policy:</b>
			<br><?php $this->showField("3_development") ?></td>
		</tr><tr>
			<td><div id="div_FLD_3_development" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("3_development_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_3_development_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "3_development") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("3_development_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Research policy:</b>
			<br><?php $this->showField("3_research_policy") ?></td>
		</tr><tr>
			<td><div id="div_FLD_3_research_policy" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("3_research_policy_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_3_research_policy_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "3_research_policy") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("3_research_policy_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>If you are still to comply with some of the minimum standards for this criterion, please attach your plan to achieve compliance.</b><br></td>
		</tr><tr>
			<td>
			Upload document electronically:
			<br>
			<?php $this->makeLink("3_achive_comp_plan_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</div>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Upload any other documentation which will indicate your compliance with this criterion.</b><br></td>
		</tr><tr>
			<td>
			Upload document electronically:
			<br>
			<?php $this->makeLink("3_additional_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>

</fieldset>

<br><br>
</td></tr></table>
<script>
//	improvement(document.defaultFrm.FLD_3_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
//	checkCriteria (document.defaultFrm.FLD_3_criteria);
</script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>
