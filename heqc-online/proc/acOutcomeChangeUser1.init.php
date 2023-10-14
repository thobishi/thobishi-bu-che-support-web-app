<?php
$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

$subject = "";
$message = "";
if ($_POST["user_ref"] != $this->currentUserID) {
	$message = $this->getTextContent("acOutcomeChangeUser1", "approveACOutcome");
	$subject = "HEQC AC meeting outcome approval";
}

$new_proc = 173;

$this->changeProcessAndUser($new_proc, $_POST["user_ref"], $subject, $message)
?>
