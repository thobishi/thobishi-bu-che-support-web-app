<a name="application_form_question6"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>6. ASSESSMENT: (criterion 6)</b> [<?php $this->popupContent("Help", "MainHelp", "", true) ?>]<br>
<br>
Taking into account the required minimum standards  for this item and the  required supporting documentation please answer the following questions:
<br><br>
<b>Minimum standards:</b> [<?php $this->popupContent("Minimum standards", "MinHelp", "", true) ?>]<br>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>6.1</b></td><td valign="top"><b>To what extent are the policies and procedures for internal assessment; internal and external moderation appropriate to the mode of delivery of the programme?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>6.2</b></td><td valign="top"><b>What mechanisms does the programme have for academic staff  to monitor student progress?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>6.3</b></td><td valign="top"><b>What mechanisms does the institution have to ensure the explicitness, validity and reliability of assessment practices?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_3_comment") ?></td>
</tr><tr>
	<td valign="top"><b>6.4</b></td><td valign="top"><b>How does the programme management relate moderator's reports and external examiner reports and how are the latter used to improve teaching and learning?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_4_comment") ?></td>
</tr><tr>
	<td valign="top"><b>6.5</b></td><td valign="top"><b>What system does the programme use for the recording of assessment results; and settling disputes in relation to assessment results?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_5_comment") ?></td>
</tr><tr>
	<td valign="top"><b>6.6</b></td><td valign="top"><b>What mechanisms does the institution have to ensure that  the assessment for the recognition of prior learning is rigorous and secure?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_6_comment") ?></td>
</tr><tr>
	<td valign="top"><b>6.7</b></td><td valign="top"><b>What mechanisms does the programme have for the development of staff competence in the assessment of RPL?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("6_7_comment") ?></td>
</tr></table>
<br><br>

<?php
	/*
	<br><br>

	<div id="notComply" style="display:none">
		<b>Please suggest improvement:</b>
	</div>
	<div id="comply" style="display:Block">
	<b>Taking into account the required minimum standards, please answer all aspects of question number 5:</b><br>
	</div>
	// $this->showField("5_comment")
	<br><br>

	<b>Please tick in the box the extent to which this programme meets the minimum standards for student assessment policies:</b><br>
	// $this->showField("5_criteria")
	<br><br>
	*/
?>

<b>In the space below indicate to what extent does your programme comply with the criterion 6:</b><br>
<?php $this->showField("6_criteria") ?>
<br><br>

<?php
	/*
	<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation:</b>
	<?php // $this->showField("5_self_evaluation") ?>
	<br><br>
	*/
?>

<fieldset>
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
<?php
	/* //HTML comment The following is for private providers  //end HTML comment */
?>
<div style="display:<?php echo $display1?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPrivate()?>
</td></tr></table>
</div>

<?php
	/* //HTML comment The following is for PUBLIC providers  //end HTML comment */
?>
<div style="display:<?php echo $display2?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPublic()?>
</td></tr></table>
</div>

<br><br>
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour">
			<b>Assessment policy:</b>
			<br>
			<?php $this->showField("6_policies") ?></td>
		</tr>
		<tr>
			<td colspan="2"><div id="div_FLD_6_policies" style="display:none">
			Please explain why not:
			<br><?php $this->showField("6_policies_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_6_policies_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "6_policies") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("6_policies_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour">
			<b>Experiential learning assessment and monitoring policy:</b>
			<br>
			<?php $this->showField("6_monitoring_policy") ?></td>
		</tr>
		<tr>
			<td colspan="2"><div id="div_FLD_6_monitoring_policy" style="display:none">
			Please explain why not:
			<br><?php $this->showField("6_monitoring_policy_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_6_monitoring_policy_doc" style="display:<?php echo echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "6_monitoring_policy") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("6_monitoring_policy_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour">
			<b>Policy and procedures for the appointment of moderators:</b>
			<br>
			<?php $this->showField("6_appointment_moderators") ?></td>
		</tr>
		<tr>
			<td colspan="2"><div id="div_FLD_6_appointment_moderators" style="display:none">
			Please explain why not:
			<br><?php $this->showField("6_appointment_moderators_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_6_appointment_moderators_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "6_appointment_moderators") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("6_appointment_moderators_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour">
			<b>Policy and procedures for the appointment of external examiners:</b>
			<br>
			<?php $this->showField("6_appointment_ext_moderators") ?></td>
		</tr>
		<tr>
			<td colspan="2"><div id="div_FLD_6_appointment_ext_moderators" style="display:none">
			Please explain why not:
			<br><?php $this->showField("6_appointment_ext_moderators_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_6_appointment_ext_moderators_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "6_appointment_ext_moderators") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("6_appointment_ext_moderators_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Upload any other documentation which will indicate your compliance with this criterion.</b><br></td>
		</tr><tr>
			<td>
			Upload document electronically:
			<br>
			<?php $this->makeLink("6_additional_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>

</fieldset>

<?php
	/*
		if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
			$cmd = explode("|", $_POST["cmd"]);
			switch ($cmd[0]) {
				case "new":
					$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
					break;
				case "del":
					$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
					break;
			}
			echo '<script>';
			echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
			echo 'document.defaultFrm.MOVETO.value = "stay";';
			echo 'document.defaultFrm.submit();';
			echo '</script>';
		}

	<br><br>
	<b>Name and profile of internal moderators for the programme:</b>
	<br><br>
	<a name="appTable_5_profile_int_moderators"></a>
	<?php 
		// Profile off internal moderators
		// appTable_5_profile_int_moderators
		$fieldsArr = array();
		$fieldsArr["name"] = array("Name", 30);
		$fieldsArr["surname"] = array("Surname", 30);
		$fieldsArr["position"] = array("Position", 60);
		$fieldsArr["field"] = array("Discipline", 60);
		$fieldsArr["highest_qual"] = "Highest Qualification";
		$fieldsArr["teaching_experience"] = array("Teaching experience in years", 2);

		$addRowText = "another internal moderator";
		echo $this->gridDisplayPerTable("Institutions_application", "appTable_5_profile_int_moderators", "appTable_5_profile_int_moderators_id", "application_ref",$fieldsArr, 5, "", "", "", "", array("teaching_experience"=>"onBlur='javascript:checkNumeric(this);'"), $addRowText, 1);

	<br><br>
	<b>Name and profile of external examiners for the programme:</b>
	<br><br>
	<a name="appTable_5_profile_ext_moderators"></a>
	<?php 
		// Profile off external moderators
		// appTable_5_profile_ext_moderators
		$fieldsArr = array();
		$fieldsArr["name"] = array("Name", 30);
		$fieldsArr["surname"] = array("Surname", 30);
		$fieldsArr["inst_affiliation"] = array("Institutional Affiliation", 20);
		$fieldsArr["position"] = array("Position", 60);
		$fieldsArr["field"] = array("Discipline", 60);
		$fieldsArr["highest_qual"] = array("Highest Qualification", 60);
		$fieldsArr["teaching_experience"] = array("Teaching experience in years", 2);
		$fieldsArr["prof_work_experience"] = array("Professional and work-place experience in years (professional and vocational programmes only)", 2);

		$addRowText = "another external examiner";
		echo $this->gridDisplayPerTable("Institutions_application", "appTable_5_profile_ext_moderators", "appTable_5_profile_ext_moderators_id", "application_ref",$fieldsArr, 5, "", "", "", "", array("teaching_experience"=>"onBlur='javascript:checkNumeric(this);'", "prof_work_experience"=>"onBlur='javascript:checkNumeric(this);'"), $addRowText, 1);
	*/
?>
<br><br>
</td></tr></table>

<?php
	/*
	<script>
		improvement(document.defaultFrm.FLD_5_criteria, document.all.notComply, document.all.comply);
		tryExpandWhyNot();
		checkCriteria (document.defaultFrm.FLD_5_criteria);
	</script>
	*/
?>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>
