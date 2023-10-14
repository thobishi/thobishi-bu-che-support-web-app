<a name="application_form_question1"></a>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>APPLICATION FORM FOR PROGRAMME ACCREDITATION:</b>
<br><br>
This part of the forms asks you to evaluate the extent to which the proposed programme fulfils the HEQC accreditation criteria.<br>
By clicking on [<b>Help</b>] you will be able to read the rationale for the criterion focus.<br><br>
[<?php $this->popupContent("Minimum standards", "MinHelp", "", true)?>] provides the full text of the minimum standards programmes are expected to meet in relation to each criterion.
<br><br>
<?php/*
	If you want to add other information to this section, click on <a href="javascript:javascript:moveto(597);">Add comment</a>
	<br><br>
	*/
?>
<b>1. PROGRAMME DESIGN: (criterion 1)</b>
<br><br>
Taking into account the minimum standards of accreditation criterion 1 and the supporting documentation you are providing, please answer the following questions.
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>1.1</b></td><td valign="top"><b>How does this programme take into account the mission of the institution and its institutional plan?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("1_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>1.2</b></td><td valign="top"><b>How does the programme meet the needs of students and other stakeholders?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("1_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>1.3</b></td><td valign="top"><b>How does it articulate with other programmes?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("1_3_comment") ?></td>
</tr><tr>
	<td valign="top"><b>1.4</b></td><td valign="top"><b>If the proposed programme is a professional degree, what mechanisms are in place to align its contents and activities to the requirements of the relevant professional body?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("1_4_comment") ?></td>
</tr></table>
<br><br>
<?php 
/*
<!--
<br>
<div id="notComply" style="display:none">
	<b>*Please suggest improvements:</b>
</div>
<div id="comply" style="display:Block">
<b>Taking into account the required minimum standards, please answer all aspects of question number 1:</b><br>
</div>
<?php // $this->showField("1_comment") ?>
<br><br>
-->
*/ ?>
<b>In the space below indicate to what extent does your programme comply with the criterion 1:</b><br>
<?php $this->showField("1_criteria") ?>
<br><br>
<?php 
/*
<!--
<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation:</b>
<?php//$this->showField("1_self_evaluation");?>
<br><br>
-->
*/ ?>
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

<table width="85%" border=0 align="center" cellpadding="2" cellspacing="2">
<td><?php $this->showInstProfileUploadedDocs($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "institution_id"));?></td>
</tr>
</table>
<br><br>

<?php 
/* <!-- The following is for private providers  --> */ ?>
<div style="display:<?php echo $display1?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPrivate()?>
</td></tr></table>
</div>

<?php 
/*
<!-- The following is for PUBLIC providers  -->
*/ ?>
<div style="display:<?php echo $display2?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPublic()?>
</td></tr></table>
</div>

<?php 
/*
<!-- The following is for private providers  -->
*/ ?>
<div style="display:<?php echo $display1?>">
<br><br>
	<ul>
<br><br>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Policy for the development of learning materials.</b>
			<br><?php $this->showField("1_prepmaterials") ?></td>
		</tr><tr>
			<td><div id="div_FLD_1_prepmaterials" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("1_prepmaterials_whyNot") ?>
			</div>
			</td>
		</tr><tr>
			<td><div id="div_FLD_1_prepmaterials_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "1_prepmaterials") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("1_prepmaterials_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Budget for the development of learning materials.</b>
			<br><?php $this->showField("1_budget") ?></td>
		</tr><tr>
			<td><div id="div_FLD_1_budget" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("1_budget_whyNot") ?>
			</div>
			</td>
		</tr><tr>
			<td><div id="div_FLD_1_budget_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "1_budget") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("1_budget_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Examples of contract arrangements with workplaces for student placements.</b>
			<br><?php $this->showField("1_contract_arragement") ?></td>
		</tr><tr>
			<td><div id="div_FLD_1_contract_arragement" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("1_contract_arragement_whyNot") ?>
			</div>
			</td>
		</tr><tr>
			<td><div id="div_FLD_1_contract_arragement_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "1_contract_arragement") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("1_contract_arragement_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
<?php 
	/*
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td  class="visi"><b>Outline of all courses and modules (core, fundamental and elective) that constitute the programme.</b>
			<br><?php $this->showField("1_elective_modules") ?></td>
		</tr><tr>
			<td><div id="div_FLD_1_elective_modules" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("1_elective_modules_whyNot") ?>
			</div>
			</td>
		</tr><tr>
			<td><div id="div_FLD_1_elective_modules_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "1_elective_modules") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("1_elective_modules_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
	*/
?>
	</ul>
</div>

	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td  class="visi"><b>Outline of all courses and modules (core, fundamental and elective) that constitute the programme.</b>
			<br><?php $this->showField("1_elective_modules") ?></td>
		</tr><tr>
			<td><div id="div_FLD_1_elective_modules" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("1_elective_modules_whyNot") ?>
			</div>
			</td>
		</tr><tr>
			<td><div id="div_FLD_1_elective_modules_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "1_elective_modules") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("1_elective_modules_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>

		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>SAQA submission.</b>
			<br><?php $this->showField("1_saqa_submission") ?></td>
		</tr><tr>
			<td><div id="div_FLD_1_saqa_submission" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("1_saqa_submission_whyNot") ?>
			</div>
			</td>
		</tr><tr>
			<td><div id="div_FLD_1_saqa_submission_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "1_saqa_submission") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("1_saqa_submission_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>

		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Lists of obligatory readings.</b>
			<br><?php $this->showField("1_outline_courses") ?></td>
		</tr><tr>
			<td><div id="div_FLD_1_outline_courses" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("1_outline_courses_whyNot") ?>
			</div>
			</td>
		</tr><tr>
			<td><div id="div_FLD_1_outline_courses_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "1_outline_courses") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("1_outline_courses_doc") ?>
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
			<?php $this->makeLink("1_additional_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>
<?php 
/*
<!-- Take out: 2004-10-26
<tr>
	<td class="oncolour"><b>Exp policy</b>
	<br><?php // $this->showField("1_exp_policy") ?></td>
</tr><tr>
<td><div id="div_FLD_1_exp_policy" style="display:none">
	Please explain why not:
	<br>
	<?php//$this->showField("1_exp_policy_whyNot") ?>
	</div>
</td>
</tr><tr>
<td><div id="div_FLD_1_exp_policy_doc" style="display:none">
	Upload document electronically:
	<br>
	<?php // $this->makeLink("1_exp_policy_doc") ?>
	</div><br>
</td>
</tr>
<tr>
	<td class="oncolour"><b>Strategic Plan</b>
	<br><?php // $this->showField("1_strategic") ?></td>
</tr><tr>
<td><div id="div_FLD_1_strategic" style="display:none">
	Please explain why not:
	<br>
	<?php // $this->showField("1_strategic_whyNot") ?>
	</div>
</td>
</tr><tr>
<td><div id="div_FLD_1_strategic_doc" style="display:none">
	Upload document electronically:
	<br>
	<?php // $this->makeLink("1_strategic_doc") ?>
	</div><br>
</td>
</tr><tr>
	<td class="oncolour"><b>Institution's Mission</b>
	<br><?php // $this->showField("1_mission") ?></td>
</tr><tr>
<td><div id="div_FLD_1_mission" style="display:none">
	Please explain why not:
	<br>
	<?php // $this->showField("1_mission_whyNot") ?>
	</div>
</td>
</tr><tr>
<td><div id="div_FLD_1_mission_doc" style="display:none">
	Upload document electronically:
	<br>
	<?php // $this->makeLink("1_mission_doc") ?>
	</div><br>
</td>
</tr>
-->
*/
?>
</fieldset>

<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td class="visi">
		<b>PROGRAMME STRUCTURE:</b>
		The programme structure tables have been replaced with the above "Outline of all courses and modules (core, fundamental and elective) that constitute the programme" field. You can however, access any information you have entered into the previous tables by clicking on Reports > Application Status and clicking on the link to the relevant application.
		<br><br>
		Copy the information from those tables and paste it into a new Word document (which will comprise of the core, fundamental and elective modules) so as to upload it into the new upload field.
		<br><br>
		Please note that only those programme structure tables that had information entered into them before this change will show in the Application Status Report.
	</td>
</tr>
</table>
<?php 
/*
<!--
<a name="appTable_1_programme_structure"></a>
<br><br>
<b>Year 1:</b>
<?php 
	$headArr = array();
	//array_push($headArr, "Module/Course name");
	array_push($headArr, "Fundamental");
	array_push($headArr, "Credits");
	array_push($headArr, "Core");
	array_push($headArr, "Credits");

	$fieldArr = array();
//	array_push($fieldArr, "type__text|name__course_name|size__30");
	array_push($fieldArr, "type__text|name__fundamental|size__40");
	array_push($fieldArr, "type__text|name__fund_credits|size__3");
	array_push($fieldArr, "type__text|name__core|size__40");
	array_push($fieldArr, "type__text|name__core_credits|size__3");

?>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->displayFixedGrid ("appTable_1_prog_structure", "appTable_1_prog_structure_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."|year__1", $fieldArr, $headArr, 12)
?>
</table>
-->
<!--
<br><br>
<b>Year 2:</b>
<?php 
	$headArr = array();
	//array_push($headArr, "Module/Course name");
	array_push($headArr, "Fundamental");
	array_push($headArr, "Credits");
	array_push($headArr, "Core");
	array_push($headArr, "Credits");

	$fieldArr = array();
	//array_push($fieldArr, "type__text|name__course_name|size__30");
	array_push($fieldArr, "type__text|name__fundamental|size__40");
	array_push($fieldArr, "type__text|name__fund_credits|size__3");
	array_push($fieldArr, "type__text|name__core|size__40");
	array_push($fieldArr, "type__text|name__core_credits|size__3");

?>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->displayFixedGrid ("appTable_1_prog_structure", "appTable_1_prog_structure_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."|year__2", $fieldArr, $headArr, 12)
?>
</table>
-->
<!--
<br><br>
<b>Year 3:</b>
<?php 
	$headArr = array();
	//array_push($headArr, "Module/Course name");
	array_push($headArr, "Fundamental");
	array_push($headArr, "Credits");
	array_push($headArr, "Core");
	array_push($headArr, "Credits");

	$fieldArr = array();
	//array_push($fieldArr, "type__text|name__course_name|size__30");
	array_push($fieldArr, "type__text|name__fundamental|size__40");
	array_push($fieldArr, "type__text|name__fund_credits|size__3");
	array_push($fieldArr, "type__text|name__core|size__40");
	array_push($fieldArr, "type__text|name__core_credits|size__3");

?>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->displayFixedGrid ("appTable_1_prog_structure", "appTable_1_prog_structure_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."|year__3", $fieldArr, $headArr, 12)
?>
</table>
-->
<!--
<br><br>
<b>Year 4:</b>
<?php 
	$headArr = array();
	//array_push($headArr, "Module/Course name");
	array_push($headArr, "Fundamental");
	array_push($headArr, "Credits");
	array_push($headArr, "Core");
	array_push($headArr, "Credits");

	$fieldArr = array();
	//array_push($fieldArr, "type__text|name__course_name|size__30");
	array_push($fieldArr, "type__text|name__fundamental|size__40");
	array_push($fieldArr, "type__text|name__fund_credits|size__3");
	array_push($fieldArr, "type__text|name__core|size__40");
	array_push($fieldArr, "type__text|name__core_credits|size__3");

?>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->displayFixedGrid ("appTable_1_prog_structure", "appTable_1_prog_structure_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."|year__4", $fieldArr, $headArr, 12);
?>
</table>
-->
*/

/*
	// Programme Structure
	// appTable_1_programme_structure
	$fieldsArr = array();
	$fieldsArr["course_name"] = array("Name of Course/Module", 60);
	$fieldsArr["obligatory_elective_selectFLD"] = "Core/Fundamental or Elective";
	$fieldsArr["total_credits"] = array("Total Credits", 5);
	$fieldsArr["nqf_level_selectFLD"] = "NQF Level";
	$fieldsArr["mode_delivery_selectFLD"] = "Mode of Delivery";
	$fieldsArr["specify_rules_textFLD"] = "Specify any rules of combination";

	//multi array with key as FLD name and values as array of DB info
	$select_arr["obligatory_elective_selectFLD"]["description_fld"] = "lkp_core_elective_desc";
	$select_arr["obligatory_elective_selectFLD"]["fld_key"] = "lkp_core_elective_id";
	$select_arr["obligatory_elective_selectFLD"]["lkp_table"] = "lkp_core_elective";
	$select_arr["obligatory_elective_selectFLD"]["lkp_condition"] = "1";
	$select_arr["obligatory_elective_selectFLD"]["order_by"] = "lkp_core_elective_id";

	$select_arr["nqf_level_selectFLD"]["description_fld"] = "NQF_level";
	$select_arr["nqf_level_selectFLD"]["fld_key"] = "NQF_id";
	$select_arr["nqf_level_selectFLD"]["lkp_table"] = "NQF_level";
	$select_arr["nqf_level_selectFLD"]["lkp_condition"] = "1";
	$select_arr["nqf_level_selectFLD"]["order_by"] = "NQF_id";

	$select_arr["mode_delivery_selectFLD"]["description_fld"] = "lkp_mode_of_delivery_desc";
	$select_arr["mode_delivery_selectFLD"]["fld_key"] = "lkp_mode_of_delivery_id";
	$select_arr["mode_delivery_selectFLD"]["lkp_table"] = "lkp_mode_of_delivery";
	$select_arr["mode_delivery_selectFLD"]["lkp_condition"] = "1";
	$select_arr["mode_delivery_selectFLD"]["order_by"] = "lkp_mode_of_delivery_desc";

//	echo $this->gridDisplay("Institutions_application", "appTable_1_programme_structure", "appTable_1_programme_structure_id", "application_ref",$fieldsArr, 5, "", "", "", $select_arr);
	$addRowText = "another course/module";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_1_programme_structure", "appTable_1_programme_structure_id", "application_ref",$fieldsArr, 5, "", "", "", $select_arr, "", $addRowText, 1);
*/

/* old make grid type. just keep it in here while testing the new grid function.
	$headArr = array();
	array_push($headArr, "Correspondence:vertical");
	array_push($headArr, "E-learning:vertical");
	array_push($headArr, "Telematic:vertical");
	array_push($headArr, "Contact:vertical");
	array_push($headArr, "Types of learning activities");
	array_push($headArr, "Hours (based on credits)");
	array_push($headArr, "% Learning Time");

	$evalArr = array();
	array_push($evalArr, "lkp_prog_struct_breakdown_desc");

	$fieldsArr = array();
	array_push($fieldsArr, "correspondance_checkbox");
	array_push($fieldsArr, "e_learning_checkbox");
	array_push($fieldsArr, "telematic_checkbox");
	array_push($fieldsArr, "contact_checkbox");
	array_push($fieldsArr, "hours");
	array_push($fieldsArr, "percentage_learning");

// HTML
<br><br>
<b>LEARNING ACTIVITIES:</b>
<br><br>
<b>Complete the following table for the whole programme</b>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
// HML END

	//$this->makeGRID("lkp_prog_struct_breakdown", $evalArr, "lkp_prog_struct_breakdown_id", "1", "appTable_1_prog_struct_breakdown", "appTable_1_prog_struct_breakdown_id", "lkp_prog_struct_breakdown_ref", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldsArr, $headArr, "", "", "", "", "", "", "", 5);

<!--
</table>
-->
	*/

	$headArr = array();
	array_push($headArr, "Correspondence:vertical");
	array_push($headArr, "E-learning:vertical");
	array_push($headArr, "Telematic:vertical");
	array_push($headArr, "Contact:vertical");
	array_push($headArr, "Types of learning activities");
	array_push($headArr, "Hours (based on credits)");
	array_push($headArr, "% Learning Time");

	$fieldArr = array();

	/*
		example of a select field
		array_push($fieldArr, "type__select|name__e_learning_checkbox|description_fld__lkp_mode_of_delivery_desc|fld_key__lkp_mode_of_delivery_id|lkp_table__lkp_mode_of_delivery|lkp_condition__1|order_by__lkp_mode_of_delivery_desc");
	*/
	array_push($fieldArr, "type__checkbox|name__correspondance_checkbox");
	array_push($fieldArr, "type__checkbox|name__e_learning_checkbox");
	array_push($fieldArr, "type__checkbox|name__telematic_checkbox");
	array_push($fieldArr, "type__checkbox|name__contact_checkbox");
	array_push($fieldArr, "type__text|name__hours|size__20");
	array_push($fieldArr, "type__text|name__percentage_learning|size__3");
?>
<br><br>
<b>LEARNING ACTIVITIES:</b>
<br><br>
<b>Complete the following table for the whole programme</b>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->gridShow("appTable_1_prog_struct_breakdown", "appTable_1_prog_struct_breakdown_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldArr, $headArr, "lkp_prog_struct_breakdown", "lkp_prog_struct_breakdown_id", "lkp_prog_struct_breakdown_desc", "lkp_prog_struct_breakdown_ref", 5);
//	$this->gridShow("lkp_prog_struct_breakdown", $evalArr, "lkp_prog_struct_breakdown_id", "1", "appTable_1_prog_struct_breakdown", "appTable_1_prog_struct_breakdown_id", "lkp_prog_struct_breakdown_ref", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldsArr, $headArr, "", "", "", "", "", "", "", 5);
?>
</table>
<br>
<table width='50%' cellpadding='2' cellspacing='2' align='center' border='1'><tr>
	<td><b>If you selected "Other" in the table above, please give a detailed explaination in the box below.</b></td>
</tr><tr>
	<td><?php $this->showField('learning_activities_other_text'); ?></td>
</tr></table>
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
/*

<!--
<br><br>
<b>WORK PLACEMENT FOR EXPERIENTIAL LEARNING: (If the information is not applicable to your programme fill in the blanks with NA)</b>
<br><br>
<a name="appTable_1_placement_work"></a>
-->
*/

	// Placement (Work)
	// appTable_1_placement_work
/*
	$fieldsArr = array();
	$fieldsArr["year_of_study"] = "Year of study when experiential learning takes place";
	$fieldsArr["duration_placement"] = "Duration of the placement";
	$fieldsArr["learning_outcomes_textFLD"] = "Expected learning outcomes";
	$fieldsArr["ass_methods_textFLD"] = array("Assessment methods", 40);
	$fieldsArr["monitor_procs_placement_textFLD"] = "Monitoring procedures";
	$fieldsArr["placement_responsible_selectFLD"] = "Placement is an institutional responsibility";
	$fieldsArr["placement_responsible_person"] = array("Who is responsible? (only if answered no in previous question)", 40);

	$select_arr["placement_responsible_selectFLD"]["description_fld"] = "lkp_yn_desc";
	$select_arr["placement_responsible_selectFLD"]["fld_key"] = "lkp_yn_id";
	$select_arr["placement_responsible_selectFLD"]["lkp_table"] = "lkp_yes_no";
	$select_arr["placement_responsible_selectFLD"]["lkp_condition"] = "1";
	$select_arr["placement_responsible_selectFLD"]["order_by"] = "lkp_yn_desc";

	$addRowText = "another year of study";
*/
	//echo $this->gridDisplayPerTable("Institutions_application", "appTable_1_placement_work", "appTable_1_placement_work_id", "application_ref",$fieldsArr, 5, "", "", "", $select_arr, array("placement_responsible_selectFLD"=>"onChange='javascript:checkPlacement(this);'"), $addRowText, 1);

/*
<!--
<br><br>
</td></tr></table>
-->
*/

	$headArr = array();
	array_push($headArr, "Year of study when experiential learning takes place");
	array_push($headArr, "Duration of the placement");
	array_push($headArr, "Expected learning outcomes");
	array_push($headArr, "Assessment methods");
	array_push($headArr, "Monitoring procedures");
	array_push($headArr, "Placement is an institutional responsibility");
	array_push($headArr, "Who is responsible? (only if answered no in previous question)");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__year_of_study");
	array_push($fieldArr, "type__text|name__duration_placement");
	array_push($fieldArr, "type__textarea|name__learning_outcomes_textFLD");
	array_push($fieldArr, "type__textarea|name__ass_methods_textFLD|size__40");
	array_push($fieldArr, "type__textarea|name__monitor_procs_placement_textFLD");
	array_push($fieldArr, "type__select|name__placement_responsible_selectFLD|onChange__javascript:checkPlacement(this);|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_desc");
	array_push($fieldArr, "type__text|name__placement_responsible_person");
?>
<br><br>
<b>WORK PLACEMENT FOR EXPERIENTIAL LEARNING: (If the information is not applicable to your programme fill in the blanks with N/A)</b>
<br><br>
<a name="appTable_1_placement_work"></a>
<?php 
	$addRowText = "another year of study";
	$this->gridShowTableByRow("appTable_1_placement_work", "appTable_1_placement_work_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldArr, $headArr, 70, 10, true, $addRowText);
?>

<script>
//	improvement(document.defaultFrm.FLD_1_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
//	checkCriteria (document.defaultFrm.FLD_1_criteria);
</script>
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

</td>
</tr></table>
