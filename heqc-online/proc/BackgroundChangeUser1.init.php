<?php
$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

$subject = "";
$message = "";
if ($_POST["user_ref"] != $this->currentUserID) {
	$message = $this->getTextContent("BackgroundTemplate", "distributeBackgroundInternal");
	$subject = "Add background request";
}

switch ($this->flowID){
case 193:
	$new_proc = 194;
	
	break;
}

$this->changeProcessAndUser($new_proc, $_POST["user_ref"], $subject, $message);

?>
