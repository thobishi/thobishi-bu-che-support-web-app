<?php 
	$req_id = $this->dbTableInfoArray["appTable_requests"]->dbTableCurrentID;

	$inst_adm = $this->getValueFromTable("appTable_requests","appTable_requests_id",$req_id,"user_to_ref");

	$message = $this->getTextContent("evalSelect8", "Request for additional information");

	$to_email = $this->getValueFromTable("users","user_id",$inst_adm,"email");

	$subject = "Request for additional information";
	$this->misMailByName ($to_email, $subject, $message, "", true);

	// look at startFlow and getStringWorkflowSettings.
	// Create an active process for the institutional user
	$this->addActiveProcesses(111, $inst_adm, 1119);
?>