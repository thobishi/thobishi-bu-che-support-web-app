<br>

<?php 
	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }

	$this->displayRelevantButtons($current_id, $this->currentUserID);

?>

<a name="application_form_question1"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

<b>B) APPLICATION FORM FOR PROGRAMME ACCREDITATION:</b>
<br><br>
This part of the form requires an evaluation of the extent to which the proposed programme fulfils the HEQC accreditation criteria.
Please note that the information provided should demonstrate compliance with the minimum standards.
<br><br>
Minimum standards provide the full text of the minimum standards programmes are expected to meet in relation to each criterion.<br><br>
<b>1. PROGRAMME DESIGN: (criterion 1)</b>
<br><br>
<fieldset>
<legend>Minimum standards</legend>
<?php echo $this->getTextContent("accForm3-1_v2", "minimumStandards"); ?>
</fieldset>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>1.1</b></td><td valign="top"><b>How does this programme fit in with the mission and plan of the institution?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php echo $this->showField("1_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>1.2</b></td><td valign="top"><b>Provide a rationale for this programme, taking into account the envisaged student intake and stakeholder needs.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php echo $this->showField("1_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>1.3</b></td><td valign="top"><b>Describe the articulation possibilities of this programme.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php echo $this->showField("1_3_comment") ?></td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
	<td valign="top"><b>1.4</b></td><td valign="top"><b>Provide the names of the modules/courses which constitute the programme - and for each course, specify:</b></td>
</tr>


<tr><td colspan="2">
	<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0">
	<?php
		$dFields = array();
		array_push($dFields, "type__text|name__course_name");

		//2010-07-28 Robin: Limit NQF level to a drop down list for version 3 applications and up.
		$app_version = $this->getValueFromTable("Institutions_application", "application_id", $current_id, "app_version");
		if ($app_version <= 2){
			array_push($dFields, "type__text|name__nqf_level");
		}
		if ($app_version >= 3){
			array_push($dFields, "type__select|name__nqf_level_ref|description_fld__NQF_level|fld_key__NQF_id|lkp_table__NQF_level|lkp_condition__1|order_by__NQF_id");
		}

		array_push($dFields, "type__text|name__fund_credits");
		array_push($dFields, "type__text|name__course_type");
		array_push($dFields, "type__text|name__year");
		array_push($dFields, "type__text|name__core_credits");

		$hFields = array("Module name", "NQF Level of the module", "Credits per module", "Compulsory/optional", "Year (1, 2, 3, 4)", "Total credits per year");

		$this->gridShowRowByRow("appTable_1_prog_structure","appTable_1_prog_structure_id","application_ref__".$current_id,$dFields,$hFields, 40, 5, "true", "true",1);
	?>
	</table>
</td></tr>

<tr><td colspan="2">
<?php 
	$headArr = array();
	array_push($headArr, "Contact:vertical");
	array_push($headArr, "Distance:vertical");
	array_push($headArr, "Other:vertical");
	array_push($headArr, "Types of learning activities");
	array_push($headArr, "% Learning Time");

	$fieldArr = array();

	array_push($fieldArr, "type__checkbox|name__contact_checkbox");
	array_push($fieldArr, "type__checkbox|name__distance_checkbox");
	array_push($fieldArr, "type__checkbox|name__other_checkbox");
	array_push($fieldArr, "type__text|name__percentage_learning|size__3");

?>
	<br><br>
	<b>1.5 LEARNING ACTIVITIES:</b>
	<br><br>
	<b>Complete the following table for the whole programme:</b>
	<br><br>
	<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
	<?php 
		$this->gridShow("appTable_1_prog_struct_breakdown", "appTable_1_prog_struct_breakdown_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldArr, $headArr, "lkp_prog_struct_breakdown", "lkp_prog_struct_breakdown_id", "lkp_prog_struct_breakdown_desc", "lkp_prog_struct_breakdown_ref", 4);
	?>
	</tr>
	<tr>
		<td colspan="7">
			<table width='100%' cellpadding='2' cellspacing='2' align='center' border='0'>
			<tr>
				<td><b>If you selected "Other" as the <u>mode of delivery</u> in the third column of the table above, please give a detailed explanation in the box below:</b></td>
				<td><b>If you selected "Other" as a <u>type of learning activity</u> in the last row of the table above, please give a detailed explanation in the box below:</b></td>
			</tr>
			<tr>
				<td><?php echo $this->showField('mode_delivery_other_text'); ?></td>
				<td><?php echo $this->showField('learning_activities_other_text'); ?></td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
</tr>

<tr>
	<td valign="top">
	<b>1.6</b></td><td valign="top"><b>Specify the programme purpose and indicate how the proposed curriculum will contribute towards the intended outcomes.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php echo $this->showField("1_4_comment_v2") ?></td>
</tr>
<tr>
	<td valign="top"><b>1.7</b></td><td valign="top"><b>Specify the rules of combination for the constituent modules/courses and, where applicable, progression rules from one year to the next.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php echo $this->showField("1_5_comment") ?></td>
</tr>
<tr>
	<td valign="top"><b>1.8</b></td><td valign="top"><b>Provide a brief explanation of how competences developed in the programme are aligned with the appropriate NQF level.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php echo $this->showField("1_6_comment") ?></td>
</tr>

<tr>
	<td valign="top"><b>1.9</b></td><td valign="top"><b>If the proposed programme is a professional programme, has approval been applied for from the relevant professional body?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php echo $this->showField("1_9_yn") ?></td>
</tr>

<?php $displayStyle = ($this->displayifConditionMetInstitutions_applications($current_id, '1_9_yn', '2') != "") ? $this->displayifConditionMetInstitutions_applications($current_id, '1_9_yn', '2') : "none"; ?>

<tr>
	<td>&nbsp;</td><td valign="top"><div style="display:<?php echo $displayStyle?>" id="1_9_prof_approval_div">Please upload letter of application or the letter of approval:<?php echo $this->makeLink("1_9_prof_approval_doc") ?></div></td>
</tr>

<tr><td colspan="2">
<?php 
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


	$headArr = array();
	array_push($headArr, "Year(s) of study when experiential learning takes place");
	array_push($headArr, "Duration of the placement");
	array_push($headArr, "Credit Value");
	array_push($headArr, "Expected learning outcomes");
	array_push($headArr, "Assessment methods");
	array_push($headArr, "Monitoring procedures");
	array_push($headArr, "Placement is an institutional responsibility");
	array_push($headArr, "Who is responsible? (only if answered \"No\" in previous question)");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__year_of_study");
	array_push($fieldArr, "type__text|name__duration_placement");
	array_push($fieldArr, "type__text|name__credit_value");
	array_push($fieldArr, "type__textarea|name__learning_outcomes_textFLD");
	array_push($fieldArr, "type__textarea|name__ass_methods_textFLD|size__40");
	array_push($fieldArr, "type__textarea|name__monitor_procs_placement_textFLD");
	array_push($fieldArr, "type__select|name__placement_responsible_selectFLD|onChange__javascript:checkPlacement(this);|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_desc");
	array_push($fieldArr, "type__text|name__placement_responsible_person");
?>

<tr>
	<td valign="top"><b>.</b></td><td valign="top"><b>Is the proposed programme an education programme?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php echo $this->showField("MRTEQ_yn") ?></td>
</tr>

<?php $displayStyle = ($this->displayifConditionMetInstitutions_applications($current_id, 'MRTEQ_yn', '2') != "") ? $this->displayifConditionMetInstitutions_applications($current_id, 'MRTEQ_yn', '2') : "none"; ?>

<tr>
	<td>&nbsp;</td><td valign="top"><div style="display:<?php echo $displayStyle?>" id="1_9_1_MRTEQ_div">Please upload the MRTEQ Endorsement letter:<?php echo $this->makeLink("department_approval_doc") ?></div></td>
</tr>

	
<tr>
	<td valign="top"><b>1.10</b></td><td valign="top"><b> WORK PLACEMENT FOR EXPERIENTIAL LEARNING:
	<br>
	Does your programme have work placement / experiential learning?</b>
	</td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php echo $this->showField("1_11_yn") ?></td>
	</tr>
	<tr>
		<td colspan="2">
<?php 
	$displayDiv1_placement_work = "none";
	if ($this->getValueFromTable("Institutions_application", "application_id", $current_id, "1_11_yn") == "2")
	{ $displayDiv1_placement_work = "block"; }
	else
	{ $displayDiv1_placement_work = "none"; }
?>
		<div style="display:<?php echo $displayDiv1_placement_work?>" id="div_placement_work">
		<i>Please note that the following table is mandatory if the programme includes experiential learning.</i></b>
		<br>
<a name="appTable_1_placement_work"></a>
<?php 
	$addRowText = "another year of study";
	$this->gridShowTableByRow("appTable_1_placement_work", "appTable_1_placement_work_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldArr, $headArr, 70, 10, true, $addRowText);
?>
</div>
</td></tr>

</table>

<br><br>
<fieldset>
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>
<br>
<?php 
	$prov_type = $this->checkAppPrivPubl($current_id);
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
*/ ?>

<div style="display:<?php echo $display1?>">
	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Policy for the development of learning materials.</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php echo $this->makeLink("1_prepmaterials_doc") ?></td>
				</tr>
			</table>
		</li>
	</ul>
</div>

<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Budget for the development of learning materials.</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php echo $this->makeLink("1_budget_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Examples of contract arrangements with workplaces for student placements.</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php echo $this->makeLink("1_contract_arragement_doc") ?><br></td>
				</tr>
			</table>
		</li>

		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Outline and outcomes of all modules (core, fundamental and optional) that constitute the programme.</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php echo $this->makeLink("1_elective_modules_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour">
						<b>SAQA submission.&nbsp;</b>
						<?php if ($this->view != 1){ ?>
							Should there be discrepancies in information on SAQA form and CHE forms or sections have not been completed in detail according to specifications in SAQA documents below the form will be returned for amendment by the institution.<br>
							Download: <a href="documents/SAQA_Qualification_template.doc">SAQA Qualification template</a><br>
							Download: <a href="documents/SAQA_Policy_Criteria_Registration_NQF.pdf">Criteria for registration of qualifications on the NQF</a><br>
							Download: <a href="documents/SAQA_submission_checklist.docx">Checklist for completing SAQA Qualification template to comply with Criteria for registration on the NQF</a><br>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php echo $this->makeLink("1_saqa_submission_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>List of prescribed and recommended readings.</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php echo $this->makeLink("1_outline_courses_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Any other documentation which will indicate your compliance with this criterion.</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php echo $this->makeLink("1_additional_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>
</fieldset>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}

	function checkPlacement (obj, obj2) {
		if (obj.value == 1) {
			alert("Please fill in the block below");
		}
	}
</script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>


</td></tr></table>
<hr>
