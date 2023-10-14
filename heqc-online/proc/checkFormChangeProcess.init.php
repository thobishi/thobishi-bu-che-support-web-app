<?php 
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	if (isset($_POST["gotoInst"]) && ($_POST["gotoInst"] == 1)) {
		$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id , "user_ref"), "email");
		$message = $this->getTextContent ("checkForm1c", "applicationToInstitution");
		$this->misMailByName($to, "Status of application for registration", $message);
		$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 0);
		$new_user = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "user_ref");
		//if is 1, old app, send to 5, if 2 is new app, send to 113
		$applicationProcess = ($this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version") == 1) ? "5" : "113";
		$this->changeActiveProcesses ($applicationProcess, $new_user);
		$this->clearWorkflowSettings ();
		$this->startFlow (__HOMEPAGE);
	}
	if (isset($_POST["doCancelProc"]) && ($_POST["doCancelProc"] == 1)) {
		$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id, "user_ref"), "email");
		$message = $this->getTextContent ("checkForm1c", "cancelApplication");
		$this->misMailByName($to, "Cancellation of application", $message);
		$this->setValueInTable("active_processes", "active_processes_id", $this->active_processes_id, "status", 1);
		$this->setValueInTable("Institutions_application", "application_id", $app_id, "application_status", "-1");
		$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 0);
	}
?>