<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>PROGRAMME INFORMATION:</b>
<br><br>
<b>1. PROGRAMME DESIGN: (criterion 1 - part 3/3)</b>
<br><br>
<b>PROGRAMME STRUCTURE:</b>
<a name="appTable_1_programme_structure"></a>
<br><br>
<?php 
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
	$fieldsArr["course_module"] = array("Name of Course/Module", 60);
	$fieldsArr["expected_outcomes_textFLD"] = "Expected Outcomes";
	$fieldsArr["assessment_method_textFLD"] = array("Assessment Method", 40);

//	echo $this->gridDisplay("Institutions_application", "appTable_1_expected_outcomes", "appTable_1_expected_outcomes_id", "application_ref",$fieldsArr, 5);
	$addRowText = "another course/module";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_1_expected_outcomes", "appTable_1_expected_outcomes_id", "application_ref",$fieldsArr, 5, "", "", "", "", "", $addRowText, 1);
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
	$headArr["Contents: "] = "1";
	$headArr["Bibliography: "] = "3";
	
	$fieldsArr = array();
	$fieldsArr["course_module"] = array("", 40);
	$fieldsArr["contents_textFLD"] = "";
	$fieldsArr["text_book_textFLD"] = array("Text book");
	$fieldsArr["recommended_reading_textFLD"] = array("Recommended reading");
	$fieldsArr["prescribed_reading_textFLD"] = array("Prescribed readings");

//	echo $this->gridDisplay("Institutions_application", "appTable_1_course_contents", "appTable_1_course_contents_id", "application_ref",$fieldsArr, 5, 0, $headArr);
	$addRowText = "another course/module";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_1_course_contents", "appTable_1_course_contents_id", "application_ref",$fieldsArr, 5, 0, $headArr, "", "", "", $addRowText, 1);
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
	$fieldsArr["ass_methods_textFLD"] = array("Assessment Methods", 40);
	$fieldsArr["monitor_procs_placement_textFLD"] = "Monitoring Procedures for placements";
	$fieldsArr["placement_responsible_selectFLD"] = "Placement is an institutional responsibility<br>(yes/no)";

	$select_arr["placement_responsible_selectFLD"]["description_fld"] = "lkp_yn_desc";
	$select_arr["placement_responsible_selectFLD"]["fld_key"] = "lkp_yn_id";
	$select_arr["placement_responsible_selectFLD"]["lkp_table"] = "lkp_yes_no";
	$select_arr["placement_responsible_selectFLD"]["lkp_condition"] = "1";
	$select_arr["placement_responsible_selectFLD"]["order_by"] = "lkp_yn_desc";
	
	$addRowText = "another year of study";
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_1_placement_work", "appTable_1_placement_work_id", "application_ref",$fieldsArr, 5, "", "", "", $select_arr, "", $addRowText, 1);
	
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
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>