<?php 
/*
	$applicationID = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$override = $this->getValueFromTable("screening", "application_ref", $applicationID, "override_saqa_nqf_registered");
	if ($override != 0) {
*/
	$to = $this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("current_saqa_user_id"), "email");
	$message = $this->getTextContent ("checkForm17", "PublicProvProgPendingSAQA");
	$this->misMailByName($to, "Programme registration on NQF", $message);
/*
	}
*/
?>