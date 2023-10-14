<?php 

	
	$this->formFields["submission_date"]->fieldValue = date("Y/m/d");
	$this->showField("submission_date");
	$this->setValueInTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "submission_date", date("Y-m-d"));


	$messagereceived = $this->getTextContent($this->template, "application has been submitted");
	$this->misMail($this->getDBsettingsValue("usr_gatekeeper"), "New application received.", $messagereceived);

	$message = $this->getTextContent($this->template, "Application Complete") ;

$this->misMail($this->currentUserID, "Your application has been submitted successfully.", $message, $this->getValueFromTable("users", "user_id",$this->currentUserID, "email"));	




	if (date("Y-m-d") < "2006-03-31") {
		$messageNOTICE = $this->getTextContent($this->template, "Application Complete Notification");
		$this->misMail($this->currentUserID, "HEQC NOTICE", $messageNOTICE, $this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("usr_registry_screener"), "email"));
	}

?>
