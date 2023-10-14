<?php 

switch ($this->flowID){
case 130:
	$InstRef = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID, "institution_ref");
	$message = $this->getTextContent("accFormCreateUsers_v2", "distributeReaccApplicInternalAdmin");
	$subject = "Application for Re-accreditation";
	break;
case 113:
default:
	$InstRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id");
	$message = $this->getTextContent("accFormCreateUsers_v2", "distributeApplicationInternalAdmin");
	$subject = "Application for Accreditation";
}

//$AdminRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref");
$AdminArr = $this->getInstitutionAdministrator(0,$InstRef);
$AdminRef = $AdminArr[0];
if ($AdminRef == 0) {
	// leave the process with the current user if the administrator cannot be determined.
	$AdminRef = $this->currentUserID;
} else {
	//$message = $this->getTextContent("accFormCreateUsers_v2", "distributeApplicationInternalAdmin");
	$this->misMail ($AdminRef, $subject, $message);
}

if (isset($_POST["LAST_WORKFLOW_ID"]) && ($_POST["LAST_WORKFLOW_ID"] > "")) {
	$ID = $_POST["LAST_WORKFLOW_ID"];
}else {
	$ID = explode('|', $_POST["PREV_WORKFLOW"]);
	$ID = $ID[1];
}

$this->addActiveProcesses($this->flowID, $AdminRef, $ID);

//2011-05-30 Robin: Added this because some users processes are not being closed even though a workflow 13 follows this init.
$this->completeActiveProcesses ();
?>
