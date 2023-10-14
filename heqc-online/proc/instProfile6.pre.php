<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		switch ($_POST["cmd"]) {
			case "new":
				$this->saveProgram("institutional_profile_overall_enroll_1999", "institution_ref", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"));
				break;
			case "del":
				if (isset($_POST["id"]) && ($_POST["id"]>"")) {
					$this->deleteProgram("institutional_profile_overall_enroll_1999", "institutional_profile_overall_enroll_1999_id", $_POST["id"]);
				}
				break;
			default:
				break;
		}
	}else {
	}

	$tableHeading = array();
	$tableHeading["Science Engineering and Technology"] = 4;
	$tableHeading["Social Sciences and Humanities"] = 4;
	
	$fieldsArr = array();
	$fieldsArr["sci_african"] = "African";
	$fieldsArr["sci_coloured"] = "Coloured";
	$fieldsArr["sci_indian"] = "Indian";
	$fieldsArr["sci_white"] = "White";
	$fieldsArr["soc_african"] = "African";
	$fieldsArr["soc_coloured"] = "Coloured";
	$fieldsArr["soc_indian"] = "Indian";
	$fieldsArr["soc_white"] = "White";
	
	$soc_sci = $this->createAcaStruct("institution_ref", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "institutional_profile_overall_enroll_1999", "institutional_profile_overall_enroll_1999_id", $fieldsArr, 5, 0, $tableHeading);
	
	$tableHeading = array();
	$tableHeading["Business and Commerce"] = 4;
	$tableHeading["Education"] = 4;
	
	$fieldsArr = array();
	$fieldsArr["bus_african"] = "African";
	$fieldsArr["bus_coloured"] = "Coloured";
	$fieldsArr["bus_indian"] = "Indian";
	$fieldsArr["bus_white"] = "White";
	$fieldsArr["edu_african"] = "African";
	$fieldsArr["edu_coloured"] = "Coloured";
	$fieldsArr["edu_indian"] = "Indian";
	$fieldsArr["edu_white"] = "White";
	
	$bus = $this->createAcaStruct("institution_ref", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "institutional_profile_overall_enroll_1999", "institutional_profile_overall_enroll_1999_id", $fieldsArr, 5, 0, $tableHeading);
?>
