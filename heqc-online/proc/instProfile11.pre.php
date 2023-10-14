<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		switch ($_POST["cmd"]) {
			case "new":
				$this->saveProgram("institutional_profile_expen_pattern_2003", "institution_ref", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"));
				break;
			case "del":
				if (isset($_POST["id"]) && ($_POST["id"]>"")) {
					$this->deleteProgram("institutional_profile_expen_pattern_2003", "institutional_profile_expen_pattern_2003_id", $_POST["id"]);
				}
				break;
			default:
				break;
		}
	}else {
	}

	$tableHeading = array();
	$tableHeading[""] = 2;
	$tableHeading[" "] = 2;
	$tableHeading["Infrastructure In R0000"] = 3;
	$tableHeading["Other In R0000"] = 1;
	
	
	$fieldsArr = array();
	$fieldsArr["name_faculty_unit"] = "Name Faculty/academic unit";
	$fieldsArr["teaching_learning"] = "Teaching and learning In Rands 00000";
	$fieldsArr["research"] = "Research R0000";
	$fieldsArr["students_bursary"] = "Students Bursaries R0000";
	$fieldsArr["infra_library"] = "Library";
	$fieldsArr["infra_IT"] = "IT";
	$fieldsArr["infra_laboratory"] = "Laboratories";
	$fieldsArr["other"] = "Specify";
	
	$table = $this->createAcaStruct("institution_ref", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "institutional_profile_expen_pattern_2003", "institutional_profile_expen_pattern_2003_id", $fieldsArr, 10, 0, $tableHeading);
?>
