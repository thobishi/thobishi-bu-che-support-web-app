<?php 

switch ($this->flowID){
case 130:
	$message = $this->getTextContent("accFormCreateUsers_v2", "distributeReaccApplicInternalColleague");
	$subject = "Application for Re-accreditation";
	break;
case 113:
default:
	$message = $this->getTextContent("accFormCreateUsers_v2", "distributeApplicationInternalColleague");
	$subject = "Application for Accreditation";
}

if ($_POST["user_ref"] != $this->currentUserID) {
//	$message = $this->getTextContent("accFormCreateUsers_v2", "distributeApplicationInternalColleague");
//	$this->misMail ($_POST["user_ref"], "Application for Accreditation", $message);
	$this->misMail ($_POST["user_ref"], $subject, $message);
}

if (isset($_POST["LAST_WORKFLOW_ID"])) {
	$prevFlow = $_POST["LAST_WORKFLOW_ID"];
} else {
	$prevArr = explode ('|', $_POST["PREV_WORKFLOW"]);
	$prevFlow  = $prevArr[1];
}


$this->addActiveProcesses($this->flowID, $_POST["user_ref"], $prevFlow);

//2011-05-30 Robin: Added this because some users processes are not being closed even though a workflow 13 follows this init.
$this->completeActiveProcesses ();
?>
