<?php
	$this->setValueInTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID, "reacc_submission_date", date("Y-m-d"));

//	$message = $this->getTextContent($this->template, "Application Complete");
//	$this->misMail($this->currentUserID, "Your re-accreditation application has been submitted successfully.", $message);

//	$message = $this->getTextContent($this->template, "application has been submitted");
//	$this->misMail($this->getDBsettingsValue("usr_gatekeeper"), "New re-accreditation application received.", $message);

?>
