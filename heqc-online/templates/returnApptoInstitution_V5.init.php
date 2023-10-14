<?php
	//$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	//$inst_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	//$inst_type = $this->getValueFromTable("HEInstitution","HEI_id",$inst_id,"priv_publ");
	//$ref = $this->getValueFromTable("Institutions_application","application_id",$app_id,"CHE_reference_code");
	//$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");

	// 2015-06-10 Robin
	//  Bypass payment for new applications for public, agricultural and paid applications
	//if ($inst_type == 2 || (strpos($ref, "PN"))){
	//	$subject = "Checklisting";
	//	$message = $this->getTextContent ("generic", "returnApplication");


		
	//		$this->changeProcessAndUser(222, $this->getDBsettingsValue("usr_registry_screener"), $subject, $message);

		
	
	//} else {
		//$subject = "Payment-private";
	//	$message = $this->getTextContent ("generic", "returnApplication");

	//	$this->changeProcessAndUser(12, $this->getDBsettingsValue("usr_registry_payment"), $subject, $message);
	//}
?>
<?php 
/*

$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

if (isset($_POST["gotoManager"]) && ($_POST["gotoManager"] == 1)) {
	$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 1);
	$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
	//$new_user = $this->functionSettings ($this->getValueFromTable("processes", "processes_id", $this->getValueFromTable("work_flows", "template", $this->template, "processes_ref"), "proscess_supervisor"));
	$new_user = $this->getDBsettingsValue($usr);
	$this->changeActiveProcesses (222, $new_user, 10978);
	//$this->changeActiveProcesses (222, $new_user, 11729);
	$this->clearWorkflowSettings ();
	$this->startFlow (__HOMEPAGE);
}
if (isset($_POST["gotoInst"]) && ($_POST["gotoInst"] == 1)) {
	$this->returnAppToInstWithPayment($app_id);
}
if (isset($_POST["doCancelProc"]) && ($_POST["doCancelProc"] == 1)) {
	$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id, "user_ref"), "email");
	$message = $this->getTextContent ("checkForm1c", "cancelApplication");
	$this->misMailByName($to, "Status of application for registration", $message);
	$this->setValueInTable("active_processes", "active_processes_id", $this->active_processes_id, "status", 1);
	$this->setValueInTable("Institutions_application", "application_id", $app_id, "application_status", '-1');
	$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 0);
}
if (((isset($_POST["gotoManager"]) && ($_POST["gotoManager"] == 0)) || (!isset($_POST["gotoManager"]))) && ((isset($_POST["doCancelProc"]) && ($_POST["doCancelProc"] == 0)) || (!isset($_POST["doCancelProc"]))) && ((isset($_POST["gotoInst"]) && ($_POST["gotoInst"] == 0)) || (!isset($_POST["gotoInst"])))) {
	//Changed payment process to before checklisting: Robin 2 October 2006
	//$this->addActiveProcesses (12, $this->getDBsettingsValue("usr_registry_payment"));
	$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 0);
}
*/

//$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 1);
	//$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
	//$new_user = $this->functionSettings ($this->getValueFromTable("processes", "processes_id", $this->getValueFromTable("work_flows", "template", $this->template, "processes_ref"), "proscess_supervisor"));
	//$new_user = $this->getDBsettingsValue($usr);
//	$this->changeActiveProcesses (222, $new_user, 10978);
	//$this->changeActiveProcesses (222, $new_user, 11729);
	//$this->clearWorkflowSettings ();
	//$this->startFlow (__HOMEPAGE)

	//$subject = "Checklisting-public";
	//$message = "Checklisting-public";
	
	//$this->changeProcessAndUser(222, $this->getDBsettingsValue("usr_registry_screener"), $subject, $message);
	

?>

<?php 
	//$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	//if (isset($_POST["gotoInst"]) && ($_POST["gotoInst"] == 1)) {
	//	$this->returnAppToInstWithPayment($app_id,"screening");
	//}
	//if (isset($_POST["doCancelProc"]) && ($_POST["doCancelProc"] == 1)) {
	//	$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id, "user_ref"), "email");
	//	$message = $this->getTextContent ("checkForm1c", "cancelApplication");
	//	$this->misMailByName($to, "Cancellation of application", $message);
	//	$this->setValueInTable("active_processes", "active_processes_id", $this->active_processes_id, "status", 1);
	//	$this->setValueInTable("Institutions_application", "application_id", $app_id, "application_status", "-1");
	//}

	//$subject = "Checklisting-public";
	////$message = "Checklisting-public";
	
	//$this->changeProcessAndUser(219, $this->getDBsettingsValue("usr_gatekeeper"), $subject, $message);

	
	$app_id  = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

//	if (isset($_POST["cancelSubmissionFlag"]) && ($_POST["cancelSubmissionFlag"] == 1)) {
		//$this->returnAppToInstBeforePayment($app_id);
	//}



	$user_arr = $this->getInstitutionAdministrator($app_id);
	
			if ($user_arr[0]==0){
				echo "Processing has been halted for the following reason: <br><br>";
				echo $user_arr[1];
				die();
			}
			$app_user_email = $this->getValueFromTable("users", "user_id",$user_arr[0], "email");
			
			$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");

			//$this->setValueInTable("Institutions_application", "application_id", $app_id, "submission_date", "1000-01-01");
			//$this->setValueInTable("Institutions_application", "application_id", $app_id, "application_printed", "0");

		//	$to = $app_user_email;
		//	$message = $this->getTextContent ("gatekeeper_comment_app", "cancelSubmission");
			//$this->misMailByName($to, "Returning application", $message);

			//$applicationProcess = ($app_version == 1) ? "5" : "113";
			if ($app_version== 1){
				$applicationProcess =  "5" ;
			}else if($app_version== 5){
				$applicationProcess = "219";
			}else{
				$applicationProcess = "113";
			}
			
			$id = $this->addActiveProcesses ($applicationProcess, $user_arr[0]);
			$this->completeActiveProcesses ();



?>
