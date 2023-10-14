<?php 

if ($_POST["user_ref"] != $this->currentUserID) {
	$message = $this->getTextContent("checkFormChangeUser1", "distributeCheckListingInternal");
	$this->misMail ($_POST["user_ref"], "HEQC Checklisting", $message);
}

$prevFlow = 0;
if (isset($_POST["LAST_WORKFLOW_ID"])) {
	$prevFlow = $_POST["LAST_WORKFLOW_ID"];
} else {
	$prevArr = explode ('|', $_POST["PREV_WORKFLOW"]);
	$prevFlow  = $prevArr[1];
}

$this->addActiveProcesses($this->flowID, $_POST["user_ref"], $prevFlow);

?>
