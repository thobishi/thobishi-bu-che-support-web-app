<a name="application_form_question4"></a>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>4. STAFF SIZE AND SENIORITY: (Criterion 4)</b> [<?php $this->popupContent("Help", "MainHelp", "", true) ?>]<br>
<br>
Taking into account the required minimum standards for the accreditation criterion on staffing, the tables of evidence and the documentation provided, please answer all aspects of question number 4.
<br><br>
<b>Minimum standards:</b> [<?php $this->popupContent("Minimum standards", "MinHelp", "", true) ?>]<br>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'><tr>
	<td valign="top"><b>4.1</b></td><td valign="top"><b>What mechanisms does the institution have to ensure that the recruitment of staff follows relevant labour legislation?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("4_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>4.2</b></td><td valign="top"><b>What mechanism does the institution have to ensure that the ratio between staff and students in the proposed programme are appropriate for the nature of the programme?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("4_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>4.3</b></td><td valign="top"><b>To what extent are the academic and support staff sufficient in size and seniority for the nature and the field of the programme?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("4_3_comment") ?></td>
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
<b>In the space below indicate to what extent does your programme comply with the criterion 4:</b><br>
<?php $this->showField("4_criteria") ?>
<br><br>
<?php/*
<!--
<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation:</b>
<?php//$this->showField("3_self_evaluation") ?>
<br><br>
-->
*/
?>

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
*/
?>
<div style="display:<?php echo $display1?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPrivate()?>
</td></tr></table>
</div>
<?php/*
<!-- The following is for PUBLIC providers  -->
*/
?>
<div style="display:<?php echo $display2?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPublic()?>
</td></tr></table>
</div>
<?php/*
<br><br>
<!-- The following is for private providers  -->
*/
?>
<div style="display:<?php echo $display1?>">
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Conditions of service:</b>
			<br><?php $this->showField("4_service") ?></td>
		</tr><tr>
			<td><div id="div_FLD_4_service" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("4_service_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_4_service_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "4_service") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("4_service_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Staff recruitment policy:</b>
			<br><?php $this->showField("4_staff_recruitment") ?></td>
		</tr><tr>
			<td><div id="div_FLD_4_staff_recruitment" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("4_staff_recruitment_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_4_staff_recruitment_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "4_staff_recruitment") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("4_staff_recruitment_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Example of contracts with academic staff:</b>
			<br><?php $this->showField("4_academic_staff_contracts") ?></td>
		</tr><tr>
			<td><div id="div_FLD_4_academic_staff_contracts" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("4_academic_staff_contracts_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_4_academic_staff_contracts_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "4_academic_staff_contracts") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("4_academic_staff_contracts_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Example of academic workload:</b>
			<br><?php $this->showField("4_academic_workload") ?></td>
		</tr><tr>
			<td><div id="div_FLD_4_academic_workload" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("4_academic_workload_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_4_academic_workload_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "4_academic_workload") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("4_academic_workload_doc") ?>
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
			<?php $this->makeLink("4_achive_comp_plan_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</div>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Please upload a list of the staff attached to the programme, indicating their highest relevant qualification.</b>
			<br><?php $this->showField("4_staff_attached_programme") ?></td>
		</tr><tr>
			<td><div id="div_FLD_4_staff_attached_programme" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("4_staff_attached_programme_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_4_staff_attached_programme_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "4_staff_attached_programme") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("4_staff_attached_programme_doc") ?>
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
			<?php $this->makeLink("4_additional_doc") ?>
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
*/
?>
<?php
/*<br><br>
<b>In the following table indicate the profile of the academic staff attached to this programme:</b>
<br><br>
<a name="appTable_3_profile_academic_staff"></a>

	// Profile off academic staff
	// appTable_3_profile_academic_staff
	$fieldsArr = array();
	$fieldsArr["name"] = array("Name", 30);
	$fieldsArr["surname"] = array("Surname", 30);
	$fieldsArr["age"] = array("Age", 3);
	$fieldsArr["gender_selectFLD"] = "Gender";
	$fieldsArr["race_selectFLD"] = "Race";
	$fieldsArr["highest_qual"] = array("Highest Qualification", 30);
	$fieldsArr["prof_work_experience"] = array("Professional and work-place experience in years (professional and vocational programmes only)", 3);
	$fieldsArr["position"] = array("Position", 30);
	$fieldsArr["nature_employment_selectFLD"] = "Nature of Employment";

	$select_arr["gender_selectFLD"]["description_fld"] = "lkp_gender_desc";
	$select_arr["gender_selectFLD"]["fld_key"] = "lkp_gender_id";
	$select_arr["gender_selectFLD"]["lkp_table"] = "lkp_gender";
	$select_arr["gender_selectFLD"]["lkp_condition"] = "1";
	$select_arr["gender_selectFLD"]["order_by"] = "lkp_gender_desc";

	$select_arr["race_selectFLD"]["description_fld"] = "lkp_race_desc";
	$select_arr["race_selectFLD"]["fld_key"] = "lkp_race_id";
	$select_arr["race_selectFLD"]["lkp_table"] = "lkp_race";
	$select_arr["race_selectFLD"]["lkp_condition"] = "1";
	$select_arr["race_selectFLD"]["order_by"] = "lkp_race_desc";

	$select_arr["nature_employment_selectFLD"]["description_fld"] = "lkp_full_part_desc";
	$select_arr["nature_employment_selectFLD"]["fld_key"] = "lkp_full_part_id";
	$select_arr["nature_employment_selectFLD"]["lkp_table"] = "lkp_full_part";
	$select_arr["nature_employment_selectFLD"]["lkp_condition"] = "1";
	$select_arr["nature_employment_selectFLD"]["order_by"] = "lkp_full_part_desc";

	$addRowText = "another staff member";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_3_profile_academic_staff", "appTable_3_profile_academic_staff_id", "application_ref",$fieldsArr, 5, "", "", "", $select_arr, "", $addRowText, 1);

<br><br>
<b>In the following table indicate the profile of the support staff attached to the programme:</b>
<br><br>
<a name="appTable_3_profile_support_staff"></a>
	// Profile off support staff
	// appTable_3_profile_support_staff
	$fieldsArr = array();
	$fieldsArr["name"] = array("Name", 30);
	$fieldsArr["surname"] = array("Surname", 30);
	$fieldsArr["age"] = array("Age", 2);
	$fieldsArr["gender_selectFLD"] = "Gender";
	$fieldsArr["race_selectFLD"] = "Race";
	$fieldsArr["highest_qual"] = array("Highest Qualification", 30);
	$fieldsArr["position"] = array("Position", 30);
	$fieldsArr["nature_employment_selectFLD"] = "Nature of Employment";

	$select_arr["gender_selectFLD"]["description_fld"] = "lkp_gender_desc";
	$select_arr["gender_selectFLD"]["fld_key"] = "lkp_gender_id";
	$select_arr["gender_selectFLD"]["lkp_table"] = "lkp_gender";
	$select_arr["gender_selectFLD"]["lkp_condition"] = "1";
	$select_arr["gender_selectFLD"]["order_by"] = "lkp_gender_desc";

	$select_arr["race_selectFLD"]["description_fld"] = "lkp_race_desc";
	$select_arr["race_selectFLD"]["fld_key"] = "lkp_race_id";
	$select_arr["race_selectFLD"]["lkp_table"] = "lkp_race";
	$select_arr["race_selectFLD"]["lkp_condition"] = "1";
	$select_arr["race_selectFLD"]["order_by"] = "lkp_race_desc";

	$select_arr["nature_employment_selectFLD"]["description_fld"] = "lkp_full_part_desc";
	$select_arr["nature_employment_selectFLD"]["fld_key"] = "lkp_full_part_id";
	$select_arr["nature_employment_selectFLD"]["lkp_table"] = "lkp_full_part";
	$select_arr["nature_employment_selectFLD"]["lkp_condition"] = "1";
	$select_arr["nature_employment_selectFLD"]["order_by"] = "lkp_full_part_desc";

	$addRowText = "another staff member";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_3_profile_support_staff", "appTable_3_profile_support_staff_id", "application_ref",$fieldsArr, 5, "", "", "", $select_arr, "", $addRowText, 1);

<br><br>
<b>In the following table indicate the number of permanent (full-time and part-time) and temporary staff attached to the programme:</b>
<br><br>
<a name="appTable_3_nr_staff_attached"></a>
	// Profile off academic staff
	// appTable_3_nr_staff_attached
	$headArr = array();
	$headArr["Academic Staff: "] = "3";
	$headArr["Admin and Support Staff: "] = "3";

	$fieldsArr = array();
	$fieldsArr["academic_staff_fulltime"] = array("Full-time", 10);
	$fieldsArr["academic_staff_parttime"] = array("Part-time", 10);
	$fieldsArr["academic_staff_temp"] = array("Temporary", 10);
	$fieldsArr["admin_support_staff_fulltime"] = array("Full-time", 10);
	$fieldsArr["admin_support_staff_parttime"] = array("Part-time", 10);
	$fieldsArr["admin_support_staff_temp"] = array("Temporary", 10);

//	$select_arr["nature_employment_selectFLD"]["description_fld"] = "lkp_full_part_desc";
//	$select_arr["nature_employment_selectFLD"]["fld_key"] = "lkp_full_part_id";
//	$select_arr["nature_employment_selectFLD"]["lkp_table"] = "lkp_full_part";
//	$select_arr["nature_employment_selectFLD"]["lkp_condition"] = "1";
//	$select_arr["nature_employment_selectFLD"]["order_by"] = "lkp_full_part_desc";

	$addRowText = "another staff member";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_3_nr_staff_attached", "appTable_3_nr_staff_attached_id", "application_ref",$fieldsArr, 5, "", $headArr, "", "", "", $addRowText, 1, false);
*/
?>
<br><br>
</td></tr></table>
<?php /*
<script>
	improvement(document.defaultFrm.FLD_3_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
	checkCriteria (document.defaultFrm.FLD_3_criteria);
</script>
*/ ?>
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
