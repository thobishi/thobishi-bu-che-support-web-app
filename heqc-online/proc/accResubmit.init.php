<?php 
	// Set proc_to_manager to 1. When Public/Private user gets Checklisting back - they have management version. Otherwise Checklister version.
	// $this->setValueInTable("screening", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "proc_to_manager", 1);

	// 2013-07-23 Robin: Decision taken that resubmissions must go back to start of Checklisting - Masego Mabaso
	$this->setValueInTable("screening", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "proc_to_manager", 0);

	// Notify current institution user that resubmission has been successful
	$message = $this->getTextContent($this->template, "Application Resubmitted");
	$this->misMail($this->currentUserID, "Your application has been re-submitted successfully.", $message);

	// Determine who the public / private administrator is
	//$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
	//$adm = $this->getDBsettingsValue($usr);

	// 2013-07-23 Robin: Decision taken that resubmissions must go back to usr_registry_screener (same person who gets them after payment)
	$reg_usr = $this->getDBsettingsValue("usr_registry_screener");

	//CC current gatekeeper
	//$gatekeeper = $this->getDBsettingsValue("usr_gatekeeper");
	//$cc_gatekeeper = $this->getValueFromTable("users", "user_id", $gatekeeper, "email");
	
	// CC Project manager who returned it to the institution
	$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
	$adm = $this->getDBsettingsValue($usr);
	$cc_adm = $this->getValueFromTable("users", "user_id", $adm, "email");
	
	// Mail public/private administrator that application has been resubmitted.
	$message = $this->getTextContent($this->template, "Application Resubmission Notification");
	$this->misMail($adm, "Application re-submission notification.", $message, $cc_adm);

	// 2013-07-23 Robin: Decision taken that resubmissions must go back to start of Checklisting - Masego Mabaso
	//$this->addActiveProcesses (7, $adm, 568, 0, false, "<<EXISTING>>", false);
	$this->addActiveProcesses (7, $reg_usr, 161, 0, false, "<<EXISTING>>", false);

?>
