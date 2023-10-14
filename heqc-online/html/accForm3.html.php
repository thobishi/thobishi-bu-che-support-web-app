<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>PROGRAMME INFORMATION:</b>
<br><br>
This part of the forms asks you to evaluate the extent to which the proposed programme fulfils the HEQC accreditation criteria.<br>
By clicking on [<?php $this->popupContent("Help", "PROGRAMME_DESIGN", "", true)?>] you will be able to read the rationale for the criterion focus.<br><br>
[<?php $this->popupContent("Minimum standards", "MinHelp", "", true)?>] provides the full text of the minimum standards programmes are expected to meet in relation to each criterion. 
If you want to add other information to this section, click on <a href="javascript:javascript:moveto(597);">Add comment</a>
<br><br>
<b>1. PROGRAMME DESIGN: (criterion 1 - part 1/3)</b>
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

<!--
<br>
<div id="notComply" style="display:none">
	<b>*Please suggest improvements:</b>
</div>
<div id="comply" style="display:Block">
<b>Taking into account the required minimum standards, please answer all aspects of he question number 1:</b><br>
</div>
<?php // $this->showField("1_comment") ?>
<br><br>

<b>Please tick in the box the extent to which this programme meets the minimum standards for programme design:</b><br>
<?php // $this->showField("1_criteria") ?>
<br><br>

<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation:</b>
<?php//$this->showField("1_self_evaluation");?>
<br><br>
-->
<fieldset>
<legend><b>Required Documentation</b></legend>
<br>

<?php $this->showMessageRequiredDocs()?>

<br><br>
	<ol>
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
			<td><div id="div_FLD_1_prepmaterials_doc" style="display:none">
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
			<td><div id="div_FLD_1_budget_doc" style="display:none">
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
			<td class="oncolour"><b>Examples of contract arrangements with Workplaces for students placements.</b>
			<br><?php $this->showField("1_contract_arragement") ?></td>
		</tr><tr>
			<td><div id="div_FLD_1_contract_arragement" style="display:none">
			Please explain why not:
			<br> 
			<?php $this->showField("1_contract_arragement_whyNot") ?>
			</div>
			</td>
		</tr><tr>
			<td><div id="div_FLD_1_contract_arragement_doc" style="display:none">
			Upload document electronically:
			<br> 
			<?php $this->makeLink("1_contract_arragement_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
	</ol>
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

<table width="85%" border=0 align="center" cellpadding="2" cellspacing="2">
<td><?php $this->showInstProfileUploadedDocs($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "institution_id"));?></td>
</tr>
</table>
</fieldset>
<br><br>
--------<br>
<b>1. PROGRAMME DESIGN: (criterion 1 - part 2/3)</b>
<br><br>
<table width="85%" border=0 cellpadding="2" cellspacing="2"><tr>
	<td><span class="specialb">Please complete the following information in relation to your institution's infrastructure.</span></td>
</tr></table>
<br>
<b>PROGRAMME LEVEL:</b>
<br><br>
<table width="85%" align="center" border=0 cellpadding="2" cellspacing="2"><tr>
	<td valign="top" class="oncolourb">Title of Proposed Programme</td>
	<td valign="top"><?php $this->showField("1_title_program") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Qualification Type</td>
	<td valign="top"><?php $this->showField("1_qual_type") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Designation</td>
	<td valign="top"><?php $this->showField("1_designation") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Qualifier</td>
	<td valign="top"><?php $this->showField("1_qualifier") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Second Qualifier</td>
	<td valign="top"><?php $this->showField("1_2nd_qualifier") ?></td>
</tr>
<!--
<tr>
	<td valign="top" class="oncolour">Total NQF Credits</td>
	<td valign="top"><span class="oncolour">Breakdown of NQF Credits at Different Levels:</span>
		<table><tr>
			<td valign="top" class="oncolour">Year 1</td>
			<td valign="top"><?php//$this->showField("1_nqf_year1") ?></td>
		</tr><tr>
			<td valign="top" class="oncolour">Year 2</td>
			<td valign="top"><?php // $this->showField("1_nqf_year2") ?></td>
		</tr><tr>
			<td valign="top" class="oncolour">Year 3</td>
			<td valign="top"><?php //$this->showField("1_nqf_year3") ?></td>
		</tr></table>
	</td>
</tr>
-->
<tr>
	<td valign="top" class="oncolourb">Minimum Duration Full Time</td>
	<td valign="top"><?php $this->showField("min_duration_full_time") ?></td>
</tr><tr>
	<td valign="top" class="oncolourb">Minimum Duration Part-time</td>
	<td valign="top"><?php $this->showField("min_duration_part_time") ?></td>
</tr></table>
<br><br>
<b>BREAKDOWN OF NQF CREDITS AT DIFFERENT LEVELS:</b>
<a name="appTable_1_nqf_breakdown_per_year"></a>
<br><br>
<?php 
	// Placement (Work)
	// appTable_1_nqf_breakdown_per_year
	$headArr = array();
	$headArr["Year"] = "1";
	$headArr["Credits per NQF Level"] = "6";
	
	$fieldsArr = array();
	$fieldsArr["year_of_study"] = array("(e.g. Year 1)", 10);
	$fieldsArr["credits_level_5"] = "Level 5";
	$fieldsArr["credits_level_6"] = "Level 6";
	$fieldsArr["credits_level_7"] = "Level 7";
	$fieldsArr["credits_level_8"] = "Level 8";
	$fieldsArr["credits_level_9"] = "Level 9";
	$fieldsArr["credits_level_10"] = "Level 10";
	
	echo $this->gridDisplay("Institutions_application", "appTable_1_nqf_breakdown_per_year", "appTable_1_nqf_breakdown_per_year_id", "application_ref",$fieldsArr, 3, 0, $headArr);
	
?>
<br><br>
--------<br>
<b>1. PROGRAMME DESIGN: (criterion 1 - part 3/3)</b>
<br><br>
<table width="85%" border=0 cellpadding="2" cellspacing="2"><tr>
	<td><span class="specialb">Please complete the following information in relation to your institution's infrastructure.</span></td>
</tr></table>
<br>
<b>PROGRAMME STRUCTURE:</b>
<a name="appTable_1_programme_structure"></a>
<br><br>
<?php 
	// Programme Structure
	// appTable_1_programme_structure
	$fieldsArr = array();
	$fieldsArr["course_name"] = array("Name of Course/Module", 20);
	$fieldsArr["obligatory_elective_selectFLD"] = "Core/Fundamental or Elective";
	$fieldsArr["total_credits"] = "Total Credits";
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
?>
<br><br>
<?php 
	$headArr = array();
	array_push($headArr, "ARTICULATION");
	array_push($headArr, "PROFESSIONAL<br>PROGRAMME");
	array_push($headArr, "NON-PROFESSIONAL<br>PROGRAMME");
	
	$evalArr = array();
	array_push($evalArr, "lkp_prog_articulation_desc");
	
	$fieldsArr = array();
	array_push($fieldsArr, "prof_prog");
	array_push($fieldsArr, "non_prof_prog");
?>
<b>PROGRAMME ARTICULATION:</b>
<br><br>
<b>In the following table indicate the name of the qualifications  with which the proposed programme will articulate.</b>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->makeGRID("lkp_prog_articulation", $evalArr, "lkp_prog_articulation_id", "1", "appTable_1_prog_articulation", "appTable_1_prog_articulation_id", "lkp_prog_articulation_ref", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldsArr, $headArr, "", 20);
?>
</table>
<br><br>
<b>Complete the following table with the expected outcomes and assessment methods of all courses/modules that constitute the programme</b>
<a name="appTable_1_expected_outcomes"></a>
<br><br>
<?php 
	// Expected outcomes & assessment methods
	// appTable_1_expected_outcomes
	$fieldsArr = array();
	$fieldsArr["course_module"] = array("Name of Course/Module", 20);
	$fieldsArr["expected_outcomes_textFLD"] = "Expected outcomes";
	$fieldsArr["assessment_method"] = array("Assessment Method", 40);

//	echo $this->gridDisplay("Institutions_application", "appTable_1_expected_outcomes", "appTable_1_expected_outcomes_id", "application_ref",$fieldsArr, 5);
	$addRowText = "another course/module";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_1_expected_outcomes", "appTable_1_expected_outcomes_id", "application_ref",$fieldsArr, 5, "", "", "", "", "", $addRowText);
?>
<br><br>
<b>Complete the following table with the contents of each course, the prescribed text book, and the recommended and prescribed readings</b>
<br><br>
<a name="appTable_1_course_contents"></a>
<?php 
	// Number and type of IT infrastructure
	//  appTable_1_course_contents
	$headArr = array();
	$headArr["Name of Course/Module"] = "1";
	$headArr["Contents"] = "1";
	$headArr["Bibliography"] = "3";
	
	$fieldsArr = array();
	$fieldsArr["course_module"] = array("", 40);
	$fieldsArr["contents_textFLD"] = "";
	$fieldsArr["text_book_textFLD"] = array("Text book");
	$fieldsArr["recommended_reading_textFLD"] = array("Recommended reading");
	$fieldsArr["prescribed_reading_textFLD"] = array("Prescribed readings");

//	echo $this->gridDisplay("Institutions_application", "appTable_1_course_contents", "appTable_1_course_contents_id", "application_ref",$fieldsArr, 5, 0, $headArr);
	$addRowText = "another course/module";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_1_course_contents", "appTable_1_course_contents_id", "application_ref",$fieldsArr, 5, 0, $headArr, "", "", "", $addRowText);
?>

<br><br>

<?php 
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
?>
<br><br>
<b>LEARNING ACTIVITIES:</b>
<br><br>
<b>Complete the following table for the whole programme</b>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->makeGRID("lkp_prog_struct_breakdown", $evalArr, "lkp_prog_struct_breakdown_id", "1", "appTable_1_prog_struct_breakdown", "appTable_1_prog_struct_breakdown_id", "lkp_prog_struct_breakdown_ref", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldsArr, $headArr, "", "", "", "", "", "", "", 5);
?>
</table>
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
?>
<br><br>
<b>WORK PLACEMENT FOR EXPERIENTIAL LEARNING:</b>
<br><br>
<a name="appTable_1_placement_work"></a>
<?php 
	// Placement (Work)
	// appTable_1_placement_work
	$fieldsArr = array();
	$fieldsArr["year_of_study"] = "Year of study when experiential learning takes place";
	$fieldsArr["duration_placement"] = "Duration of placement";
	$fieldsArr["learning_outcomes_textFLD"] = "Learning Outcomes";
	$fieldsArr["ass_methods"] = array("Assessment Methods", 40);
	$fieldsArr["monitor_procs_placement_textFLD"] = "Monitoring Procedures for placements";
	$fieldsArr["placement_responsible_selectFLD"] = "Placement is an institutional responsibility<br>(yes/no)";

	$select_arr["placement_responsible_selectFLD"]["description_fld"] = "lkp_yn_desc";
	$select_arr["placement_responsible_selectFLD"]["fld_key"] = "lkp_yn_id";
	$select_arr["placement_responsible_selectFLD"]["lkp_table"] = "lkp_yes_no";
	$select_arr["placement_responsible_selectFLD"]["lkp_condition"] = "1";
	$select_arr["placement_responsible_selectFLD"]["order_by"] = "lkp_yn_desc";
	
	$addRowText = "another year of study";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_1_placement_work", "appTable_1_placement_work_id", "application_ref",$fieldsArr, 5, "", "", "", $select_arr, "", $addRowText);
	
?>

<br><br>
</td></tr></table>
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
</script>
