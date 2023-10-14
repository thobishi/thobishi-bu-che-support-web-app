<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		switch ($_POST["cmd"]) {
			case "new":
				$this->saveProgram("institutional_profile_aca_struct", "institution_ref", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"));
				break;
			case "del":
				if (isset($_POST["id"]) && ($_POST["id"]>"")) {
					$this->deleteProgram("institutional_profile_aca_struct", "institutional_profile_aca_struct_id", $_POST["id"]);
				}
				break;
			default:
				break;
		}
	}else {
	}

	$tableHeading = array();
	$tableHeading["Faculty Information "] = 3;
	$tableHeading["Programs Offered"] = 4;
	
	$fieldsArr = array();
	$fieldsArr["faculty_name"] = "Faculty Name";
	$fieldsArr["school_name"] = "School Name";
	$fieldsArr["dept_name"] = "Department Name";
	$fieldsArr["prog_under_name"] = "Name";
	$fieldsArr["prog_under_nqf_ref"] = "NQF Level";
	$fieldsArr["prog_post_name"] = "Name";
	$fieldsArr["prog_post_nqf_ref"] = "NQF Level";

	$program = $this->createAcaStruct("institution_ref", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "institutional_profile_aca_struct", "institutional_profile_aca_struct_id", $fieldsArr, 10, 0, $tableHeading);
?>
