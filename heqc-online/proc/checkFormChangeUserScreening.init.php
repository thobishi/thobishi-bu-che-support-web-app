<?php 

if ($_POST["user_ref"] != $this->currentUserID) {
	$message = $this->getTextContent("checkFormChangeUserScreening", "distributeScreeningInternal");
	$this->misMail ($_POST["user_ref"], "HEQC Screening", $message);
}

$this->addActiveProcesses($this->flowID, $_POST["user_ref"], $_POST["LAST_WORKFLOW_ID"]);

?>
