<?php 

if ($_POST["user_ref"] != $this->currentUserID) {
	$message = $this->getTextContent("accFormCreateUsers", "distributeApplicationInternal");
	$this->misMail ($_POST["user_ref"], "Application for Accreditation", $message);
}

if (isset($_POST["LAST_WORKFLOW_ID"])) {
	$prevFlow = $_POST["LAST_WORKFLOW_ID"];
} else {
	$prevArr = explode ('|', $_POST["PREV_WORKFLOW"]);
	$prevFlow  = $prevArr[1];
}


$this->addActiveProcesses($this->flowID, $_POST["user_ref"], $prevFlow);
?>
