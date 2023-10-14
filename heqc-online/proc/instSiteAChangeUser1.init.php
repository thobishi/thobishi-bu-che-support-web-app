<?php
$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;

$subject = "";
$message = "";
if ($_POST["user_ref"] != $this->currentUserID) {
	$message = $this->getTextContent("instSiteAChangeUser1", "Site AC outcome approval");
	$subject = "AC meeting site outcome approval";
}

$new_proc = 185;

$this->changeProcessAndUser($new_proc, $_POST["user_ref"], $subject, $message)
?>
