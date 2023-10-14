<?php 
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	if (isset($_POST["gotoInst"]) && ($_POST["gotoInst"] == 1)) {
		$this->returnAppToInstWithPayment($app_id,"screening");
	}
	if (isset($_POST["doCancelProc"]) && ($_POST["doCancelProc"] == 1)) {
		$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id, "user_ref"), "email");
		$message = $this->getTextContent ("checkForm1c", "cancelApplication");
		$this->misMailByName($to, "Cancellation of application", $message);
		$this->setValueInTable("active_processes", "active_processes_id", $this->active_processes_id, "status", 1);
		$this->setValueInTable("Institutions_application", "application_id", $app_id, "application_status", "-1");
	}
?>