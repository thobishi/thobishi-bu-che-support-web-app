<?php
$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
$new_proc = 195;

$subject = "";
$message = "";
if ($_POST["user_ref"] != $this->currentUserID) {
	$message = $this->getTextContent("conditionsChangeUser1", "distributeConditionInternal");
	$subject = "Conditions request";
}

$this->changeProcessAndUser($new_proc, $_POST["user_ref"], $subject, $message)

?>
