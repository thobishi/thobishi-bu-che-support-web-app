<?php 

$AdminRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref");

$message = $this->getTextContent("accFormCreateUsers", "distributeApplicationInternal");
$this->misMail ($AdminRef, "Application for Accreditation", $message);

if (isset($_POST["LAST_WORKFLOW_ID"]) && ($_POST["LAST_WORKFLOW_ID"] > "")) {
	$ID = $_POST["LAST_WORKFLOW_ID"];
}else {
	$ID = explode('|', $_POST["PREV_WORKFLOW"]);
	$ID = $ID[1];
}

$this->addActiveProcesses($this->flowID, $AdminRef, $ID);

?>
