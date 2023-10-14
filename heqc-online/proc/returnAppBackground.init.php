<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 0);

	$user = readPost("user_ref");

	$subject = "Background request";
	$message = $this->getTextContent ("generic", "colleagueRequest");

	$flow = '10978';

	$this->changeProcessAndUser(7, $user, $subject, $message,$flow);
?>