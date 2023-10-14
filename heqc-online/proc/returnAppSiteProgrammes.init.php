<?php
$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;

$subject = "";
$message = "";
if ($_POST["user_ref"] != $this->currentUserID) {
	$message = $this->getTextContent ("generic", "returnSiteApplication");
	$subject = "Programmes per Site Decisions";
}

$new_proc = 189;

$this->changeProcessAndUser($new_proc, $_POST["user_ref"], $subject, $message)
?>
