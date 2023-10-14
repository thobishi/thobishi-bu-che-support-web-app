<?php 

	if (isset($_POST["FLD_inst_have_application_registration"]) && ($_POST["FLD_inst_have_application_registration"] > 0)) {
		$this->setValueInTable ("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "inst_have_application_pending", 0);
	}
	if (isset($_POST["FLD_inst_have_application_pending"]) && ($_POST["FLD_inst_have_application_pending"] > 0)) {
		$this->setValueInTable ("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "inst_have_application_registration", 0);
	}

	$info_str = "Please click 'Next' to continue.";
	$type = "";
	if ($this->getValueFromTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "inst_have_application_registration") == 1) {
		$info_str = "Send a letter to the institution indicating that the DoE has no record of its registration as a private provider of higher education and that the accreditation process cannot continue until the HEQC has a registration number provided by the DoE.";
		$type = "application_registration";
	}else if ($this->getValueFromTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "inst_have_application_pending") == 1) {
		$info_str = "Send a letter to the institution indicating that the HEQC cannot continue the accreditation process until there is at least a record that the institution has applied for registration as a private provider with the DoE.";
		$type = "application_pending";
	}
?>