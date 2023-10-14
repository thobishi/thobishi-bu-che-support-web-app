<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		switch ($_POST["cmd"]) {
			case "new":
				$this->saveProgram("institutional_profile_full_equiv_1999", "institution_ref", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"));
				break;
			case "del":
				if (isset($_POST["id"]) && ($_POST["id"]>"")) {
					$this->deleteProgram("institutional_profile_full_equiv_1999", "institutional_profile_full_equiv_1999_id", $_POST["id"]);
				}
				break;
			default:
				break;
		}
	}else {
	}

	$tableHeading = array();
	$tableHeading["Sciences Engineering and Technology"] = 2;
	$tableHeading["Social Sciences and Humanities"] = 2;
	$tableHeading["Education"] = 2;
	$tableHeading["Business Commerce"] = 2;
	$tableHeading["Total"] = 2;
	
	
	$fieldsArr = array();
	$fieldsArr["sci_contact"] = "Contact";
	$fieldsArr["sci_dist"] = "Distance";
	$fieldsArr["soc_contact"] = "Contact";
	$fieldsArr["soc_dist"] = "Distance";
	$fieldsArr["edu_contact"] = "Contact";
	$fieldsArr["edu_dist"] = "Distance";
	$fieldsArr["bus_contact"] = "Contact";
	$fieldsArr["bus_dist"] = "Distance";
	$fieldsArr["total_contact"] = "Contact";
	$fieldsArr["total_dist"] = "Distance";
	$fieldsArr["perc_contact"] = "% Contact";
	$fieldsArr["perc_dist"] = "% Distance";
	
	$table = $this->createAcaStruct("institution_ref", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "institutional_profile_full_equiv_1999", "institutional_profile_full_equiv_1999_id", $fieldsArr, 5, 0, $tableHeading);
?>
