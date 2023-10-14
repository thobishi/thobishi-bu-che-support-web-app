<?php
$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

$subject = "";
$message = "";
if ($_POST["user_ref"] != $this->currentUserID) {
	$message = $this->getTextContent("acOutcomeChangeUser1", "approveACOutcome");
	$subject = "HEQC meeting outcome approval";
}

$new_proc = 206;

$this->changeProcessAndUser($new_proc, $_POST["user_ref"], $subject, $message)
?>
