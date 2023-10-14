<?php 
	$type = "";
	if ($this->getValueFromTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "inst_have_application_registration") == 1) {
		$type = "application_registration";
	}
	
	if ($this->getValueFromTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "inst_have_application_pending") == 1) {
		$type = "application_pending";
	}
	
	if ($type > "") {
		$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");
		$message = $this->getTextContent("checkForm30", $type);
		$this->misMail($to, $this->getDBsettingsValue("default_email_subject"), $message);
	}
?>