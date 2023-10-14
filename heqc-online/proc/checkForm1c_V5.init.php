<?php 


	//$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	//if (isset($_POST["gotoManager"]) && ($_POST["gotoManager"] == 1)) {
	//	$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 1);
	//	$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
		//$new_user = $this->functionSettings ($this->getValueFromTable("processes", "processes_id", $this->getValueFromTable("work_flows", "template", $this->template, "processes_ref"), "proscess_supervisor"));
	//	$new_user = $this->getDBsettingsValue($usr);
	//	$this->changeActiveProcesses (7, $new_user, 10978);
	//	$this->clearWorkflowSettings ();
	//	$this->startFlow (__HOMEPAGE);
	//}
	//if (isset($_POST["gotoInst"]) && ($_POST["gotoInst"] == 1)) {
	//	$this->returnAppToInstWithPayment($app_id);
	//}  
	if (isset($_POST["doCancelProc"]) || ($_POST["doCancelProc"] == 1)) {
		$subject = "Checklisting-public";
		$message = "Checklisting-public";
		
		$this->changeProcessAndUser(221, $this->getDBsettingsValue("usr_registry_screener"), $subject, $message);
	}
	//if (((isset($_POST["gotoManager"]) && ($_POST["gotoManager"] == 0)) || (!isset($_POST["gotoManager"]))) && ((isset($_POST["doCancelProc"]) && ($_POST["doCancelProc"] == 0)) || (!isset($_POST["doCancelProc"]))) && ((isset($_POST["gotoInst"]) && ($_POST["gotoInst"] == 0)) || (!isset($_POST["gotoInst"])))) {
		//Changed payment process to before checklisting: Robin 2 October 2006
		//$this->addActiveProcesses (12, $this->getDBsettingsValue("usr_registry_payment"));
		//$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 0);
	//}
?>