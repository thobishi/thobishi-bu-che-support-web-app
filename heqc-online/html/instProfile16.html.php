<a name="application_form_question12"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<b>12. IT INFRASTRUCTURE:</b>
<br><br>
<i>Please complete the following information in relation to your institution's infrastructure.</i>
<br><br>
<i>Provide details of computer resources available to staff and to students.</i>
<br><br>
<?php $this->showField("improvements_computer_facilities"); ?>
<br><br>
<i>If the institution has more than one type of computer, please press the "Add Type" button to Add information about all other computer types. </i>
<br><br>
<a name="institutional_profile_nr_type_IT_infrastructure"></a>
<?php 
	// Number and type of IT infrastructure
	//  institutional_profile_nr_type_IT_infrastructure
/*
	$headArr = array();
	$headArr["Location <br>(e.g. Social Science Faculty)"] = "1";
	$headArr["Number of New Computers <br>(less than 18 months old)"] = "1";
	$headArr["Number of Std. Computers <br>(from 18 months to 4 years old)"] = "1";
	$headArr["Number of Old Computers <br>(older than 4 years)"] = "1";
	$headArr["Number of Technical Support Staff "] = "1";
	$headArr["Usage:"] = "2";

	$fieldsArr = array();
	$fieldsArr["location"] = array("", 50);
	$fieldsArr["noOf_comp_new"] = array("", 5);
	$fieldsArr["noOf_comp_std"] = array("", 5);
	$fieldsArr["noOf_comp_old"] = array("", 5);
	$fieldsArr["nr_tech_staff"] = array("", 5);
	$fieldsArr["budget_staff"] = array("Number of Computers used by Academic Staff", 5);
	$fieldsArr["budget_students"] = array("Number of Computers used by Students", 5);

	echo $this->gridDisplayPerTable("institutional_profile", "institutional_profile_nr_type_IT_infrastructure", "institutional_profile_nr_type_IT_infrastructure_id", "institution_ref",$fieldsArr, 5, 0, $headArr, "", "", "", "", 1);
*/
	$headArr = array();
	array_push($headArr, "Location <br>(e.g. Social Science Faculty)");
	array_push($headArr, "Number of New Computers <br>(less than 18 months old)");
	array_push($headArr, "Number of Std. Computers <br>(from 18 months to 4 years old)");
	array_push($headArr, "Number of Old Computers <br>(older than 4 years)");
	array_push($headArr, "Number of Technical Support Staff");
	array_push($headArr, "Usage: Number of Computers used by Academic Staff");
	array_push($headArr, "Usage: Number of Computers used by Students");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__location|size__50");
	array_push($fieldArr, "type__text|name__noOf_comp_new|size__50");
	array_push($fieldArr, "type__text|name__noOf_comp_std|size__5");
	array_push($fieldArr, "type__text|name__noOf_comp_old|size__5");
	array_push($fieldArr, "type__text|name__nr_tech_staff|size__5");
	array_push($fieldArr, "type__text|name__budget_staff|size__5");
	array_push($fieldArr, "type__text|name__budget_students|size__5");

	$this->gridShowTableByRow("institutional_profile_nr_type_IT_infrastructure", "institutional_profile_nr_type_IT_infrastructure_id", "institution_ref__".$this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID, $fieldArr, $headArr, 70, 10, true);
?>
<br><br>
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
</td></tr></table>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
