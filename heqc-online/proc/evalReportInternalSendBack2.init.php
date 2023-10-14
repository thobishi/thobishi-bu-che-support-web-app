<?php 
	$this->setValueInTable("application_summery_comments_internal", "application_sum_id", $this->dbTableInfoArray["application_summery_comments_internal"]->dbTableCurrentID, "is_at_manager", 0);
	$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
	$new_user = $this->getDBsettingsValue($usr);
	$this->addActiveProcesses (75, $new_user);
	$message = $this->getTextContent ("evalReportInternalSendBack1", "Internal evaluation screening");
	$this->misMail($new_user, "Internal evaluation screening", $message);
?>